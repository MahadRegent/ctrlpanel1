<?php

namespace App\Extensions\PaymentGateways\Morune;

use App\Classes\PaymentExtension;
use App\Enums\PaymentStatus;
use App\Events\PaymentEvent;
use App\Events\UserUpdateCreditsEvent;
use App\Models\Payment;
use App\Models\ShopProduct;
use App\Models\User;
use App\Notifications\ConfirmPaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MoruneExtension extends PaymentExtension
{
    public static function getConfig(): array
    {
        return [
            "name" => "Morune",
            "RoutesIgnoreCsrf" => [
                "payment/MoruneWebhooks",
            ],
        ];
    }

    public static function getRedirectUrl(Payment $payment, ShopProduct $shopProduct, string $totalPriceString): string
    {
        try {
            $settings = new MoruneSettings();

            // --- ОТЛАДОЧНАЯ СТРОКА ---
            // Логируем свойства загруженного объекта MoruneSettings
            // Эта строка поможет нам понять, какие свойства видит Laravel
            Log::info('DEBUG: MoruneSettings object properties:', (array) $settings);
            // --- КОНЕЦ ОТЛАДОЧНОЙ СТРОКИ ---

            if (!$settings->enabled) {
                Log::error('Morune payment gateway is disabled');
                throw new Exception('Morune payment gateway is disabled');
            }

            $shopId = $settings->shop_id;
            $secretKey = self::getSecretKey();

            // URL-адреса теперь берутся из маршрутов Laravel, а не из настроек
            $successUrl = route('home');
            $failUrl = route('payment.Cancel');
            $hookUrl = route('payment.MoruneWebhooks');

            if (empty($shopId) || empty($secretKey)) { // Проверки только для shopId и secretKey
                Log::error('Morune settings incomplete', [
                    'shop_id' => $shopId,
                    'secret_key' => !empty($secretKey),
                ]);
                throw new Exception('Morune shop ID or secret key not configured');
            }

            $totalPrice = (float) $totalPriceString;
            if ($totalPrice <= 0) {
                Log::error('Invalid payment amount', ['amount' => $totalPriceString]);
                throw new Exception('Invalid price amount');
            }

            $amount = number_format($totalPrice / 1, 2, '.', '');
            $currency = $shopProduct->currency_code ?? 'RUB';
            $orderId = 'order_' . $payment->id;

            // Абсолютный путь к Python-скрипту
            $scriptPath = '/var/www/ctrlpanel/app/Extensions/PaymentGateways/Morune/create_morune_payment.py';
            $command = "python3 " . escapeshellarg($scriptPath) . " " .
                                 escapeshellarg($shopId) . " " .
                                 escapeshellarg($secretKey) . " " .
                                 escapeshellarg($amount) . " " .
                                 escapeshellarg($currency) . " " .
                                 escapeshellarg($orderId) . " " .
                                 escapeshellarg($successUrl) . " " .
                                 escapeshellarg($failUrl) . " " .
                                 escapeshellarg($hookUrl);

            Log::info('Executing Python script', ['command' => $command]);

            $output = [];
            $returnVar = 0;
            exec($command . ' 2>&1', $output, $returnVar);

            if ($returnVar !== 0) {
                Log::error('Python script failed', ['output' => $output, 'return_var' => $returnVar]);
                throw new Exception('Ошибка выполнения Python-скрипта: Просмотрите /var/www/ctrlpanel/storage/logs/laravel.log для деталей');
            }

            $response = json_decode(implode("\n", $output), true);
            if (!$response) {
                Log::error('Invalid JSON output from Python script', ['output' => $output]);
                throw new Exception('Некорректный JSON-ответ от Python-скрипта');
            }

            // Проверяем статус и наличие URL в ответе от Python-скрипта
            if (isset($response['status']) && $response['status'] == 200 && isset($response['data']['url'])) {
                $payment->update(['invoice_id' => $response['data']['id']]);
                Log::info('Payment URL generated', ['url' => $response['data']['url'], 'payment_id' => $payment->id]);
                return $response['data']['url'];
            } else {
                $errorMessage = $response['error'] ?? 'Неизвестная ошибка в ответе Python-скрипта.';
                Log::error('Error from Python script response', ['response' => $response, 'error_message' => $errorMessage]);
                throw new Exception('Ошибка в Python-скрипте: ' . $errorMessage);
            }

        } catch (Exception $e) {
            Log::error('Exception in Morune getRedirectUrl', [
                'message' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
            throw $e;
        }
    }

    public static function MoruneWebhooks(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('x-api-sha256-signature');
            $secretKey = self::getSecretKey();

            $expectedSignature = hash_hmac('sha256', $payload, $secretKey);
            if (!hash_equals($expectedSignature, $signature)) {
                Log::error('Invalid Morune webhook signature', ['signature' => $signature]);
                abort(400, 'Invalid signature');
            }

            $data = json_decode($payload, true);
            if (!$data || !in_array($data['type'], [1, 2])) {
                Log::error('Invalid Morune webhook payload', ['payload' => $payload]);
                abort(400, 'Invalid payload or type');
            }

            if ($data['type'] == 1) {
                $orderId = $data['order_id'] ?? null;
                if (!$orderId || !str_starts_with($orderId, 'order_')) {
                    Log::error('Missing or invalid order_id in Morune webhook', ['order_id' => $orderId]);
                    abort(400, 'Missing or invalid order_id');
                }
                $paymentId = (int) substr($orderId, 6);

                $payment = Payment::findOrFail($paymentId);
                $status = $data['status'];

                switch ($status) {
                    case 'success':
                        $payment->status = PaymentStatus::PAID;
                        break;
                    case 'fail':
                        $payment->status = PaymentStatus::FAILED;
                        break;
                    case 'expired':
                        $payment->status = PaymentStatus::EXPIRED;
                        break;
                    case 'refund':
                        $payment->status = PaymentStatus::REFUNDED;
                        break;
                    default:
                        Log::error('Unknown Morune webhook status', ['status' => $status]);
                        abort(400, 'Unknown status');
                }

                $payment->save();
                Log::info('Morune webhook processed', ['payment_id' => $paymentId, 'status' => $status]);

                if ($status == 'success') {
                    $user = $payment->user;
                    $shopProduct = ShopProduct::findOrFail($payment->shop_item_product_id);
                    $user->notify(new ConfirmPaymentNotification($payment));
                    event(new UserUpdateCreditsEvent($user));
                    event(new PaymentEvent($user, $payment, $shopProduct));
                }
            }

            return response()->json(['success' => true], 200);
        } catch (Exception $e) {
            Log::error('Exception in Morune webhook handling', ['message' => $e->getMessage()]);
            abort(500, 'Webhook processing failed');
        }
    }

    protected static function getSecretKey(): string
    {
        $settings = new MoruneSettings();
        $key = config('app.env') == 'local' ? ($settings->test_secret_key ?? $settings->secret_key) : $settings->secret_key;
        if (empty($key)) {
            Log::error('Morune secret key not configured');
            throw new Exception('Secret key not configured');
        }
        return $key;
    }
}
