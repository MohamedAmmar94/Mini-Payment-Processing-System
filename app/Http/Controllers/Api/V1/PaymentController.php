<?php
namespace App\Http\Controllers\Api\V1;

use App\Contracts\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaymentRequest;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller {
    public function store(PaymentRequest $request,
        Invoice $invoice,
        PaymentService $paymentService): JsonResponse {
        // dd("ss");
        $payment = $paymentService->createPayment(
            $invoice,
            $request->validated(),
            $request->header('Idempotency-Key')
        );

        return response()->json([
            'data' => $payment,
        ], 201);

    }
    public function retrive_payment(PaymentGatewayInterface $gateway) {
        $gateway->retrive();
    }
}
