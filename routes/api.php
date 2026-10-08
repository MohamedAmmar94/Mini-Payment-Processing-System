<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\TapWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['prefix' => 'v1'], function () {
    Route::get("customers", [CustomerController::class, "index"]);
    Route::get("customers/{id}", [CustomerController::class, "show"]);
    Route::post("customers", [CustomerController::class, "store"]);
    Route::post("customers/{id}", [CustomerController::class, "update"]);
    Route::delete("customers/{id}", [CustomerController::class, "destroy"]);

    Route::get("invoices", [InvoiceController::class, "index"]);
    Route::get("invoices/{id}", [InvoiceController::class, "show"]);
    Route::post("invoices", [InvoiceController::class, "store"]);
    Route::post("invoices/{id}", [InvoiceController::class, "update"]);
    Route::delete("invoices/{id}", [InvoiceController::class, "destroy"]);

    Route::get("payments", [PaymentController::class, "index"]);
    Route::get("payments/retrive", [PaymentController::class, "retrive_payment"]);
    Route::get("payments/{id}", [PaymentController::class, "show"]);
    Route::post("payments", [PaymentController::class, "store"]);
    Route::post("payments/{id}", [PaymentController::class, "update"]);
    Route::delete("payments/{id}", [PaymentController::class, "destroy"]);

    Route::post(
        'invoices/{invoice}/payments',
        [PaymentController::class, 'store']
    );

});
Route::any('webhooks/tap', [TapWebhookController::class, 'handle']);
