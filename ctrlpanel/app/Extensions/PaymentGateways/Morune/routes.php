<?php

use Illuminate\Support\Facades\Route;
use App\Extensions\PaymentGateways\Morune\MoruneExtension;

Route::post(
    'payment/MoruneWebhooks',
    function () {
        return MoruneExtension::MoruneWebhooks(request());
    }
)->name('payment.MoruneWebhooks');
