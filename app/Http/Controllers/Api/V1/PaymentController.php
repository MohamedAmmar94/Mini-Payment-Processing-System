<?php
namespace App\Http\Controllers\Api\V1;

use App\Contracts\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaymentDatatableRequest;
use App\Http\Requests\Api\V1\PaymentRequest;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller {
    public function __construct(protected PaymentService $paymentService) {
    }
    public function index(PaymentDatatableRequest $request) {
        $res = $this->paymentService->datatables($request->validated());
        return $this->successResponse($res, 'Payments retrieved successfully.', 200);
    }
    public function store(PaymentRequest $request,
        Invoice $invoice): JsonResponse {
        $payment = $this->paymentService->createPayment(
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
