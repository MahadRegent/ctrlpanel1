<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Settings\GeneralSettings;
use App\Settings\UserSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    /** Display a listing of the resource. */
    public function index(UserSettings $user_settings, GeneralSettings $general_settings)
    {
        $isStoreEnabled = $general_settings->store_enabled;

        //Required Verification for creating an server
        if ($user_settings->force_email_verification && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('profile.index')->with('error', __('You are required to verify your email address before you can purchase credits.'));
        }

        //Required Verification for creating an server
        if ($user_settings->force_discord_verification && !Auth::user()->discordUser) {
            return redirect()->route('profile.index')->with('error', __('You are required to link your discord account before you can purchase Credits'));
        }

        return view('store.index')->with([
            'products' => ShopProduct::where('disabled', '=', false)->orderBy('type', 'asc')->orderBy('price', 'asc')->get(),
            'isStoreEnabled' => $isStoreEnabled,
            'credits_display_name' => $general_settings->credits_display_name
        ]);
    }

    /**
     * Create a custom top-up product and redirect to checkout
     *
     * @param Request $request
     * @param GeneralSettings $general_settings
     * @return RedirectResponse
     */
    public function createCustomTopUp(Request $request, GeneralSettings $general_settings): RedirectResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'custom_amount' => [
                'required',
                'numeric',
                'min:1',
                'max:100000',
                'regex:/^\d+(\.\d{1,2})?$/'
            ]
        ], [
            'custom_amount.required' => 'Пожалуйста, укажите сумму пополнения.',
            'custom_amount.numeric' => 'Сумма должна быть числом.',
            'custom_amount.min' => 'Минимальная сумма пополнения: 1 рубль.',
            'custom_amount.max' => 'Максимальная сумма пополнения: 100,000 рублей.',
            'custom_amount.regex' => 'Сумма может содержать только цифры и не более 2 знаков после запятой.'
        ]);

        if ($validator->fails()) {
            return redirect()->route('store.index')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Ошибка валидации данных.');
        }

        $customAmount = floatval($request->input('custom_amount'));

        // Create a temporary shop product for the custom top-up
        $shopProduct = ShopProduct::create([
            'type' => 'Credits',
            'name' => 'Кастомное пополнение баланса',
            'price' => $customAmount,
            'currency_code' => 'RUB', // Рубли
            'quantity' => $customAmount, // 1 рубль = 1 кредит
            'description' => 'Пополнение баланса на ' . $customAmount . ' кредитов',
            'display' => $customAmount . ' кредитов',
            'disabled' => false,
        ]);

        // Redirect to checkout with the created product
        return redirect()->route('checkout', $shopProduct->id);
    }
}
