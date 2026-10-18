<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaymentWebhookDatatableRequest;
use App\Services\PaymentWebhookService;

class PaymentWebhookEventController extends Controller {
    public function __construct(protected PaymentWebhookService $paymentWebhookService) {
    }
    public function index(PaymentWebhookDatatableRequest $request) {
        $filters = $request->validated();
        $res     = $this->paymentWebhookService->datatables($filters);
        return $this->successResponse($res, 'Payment webhook events retrieved successfully.', 200);
    }
}
