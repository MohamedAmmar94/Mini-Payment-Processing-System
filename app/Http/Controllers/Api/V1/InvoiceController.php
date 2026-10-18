<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InvoiceDatatableRequest;
use App\Http\Requests\Api\V1\InvoiceRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller {
    public function __construct(protected InvoiceService $invoiceService) {
    }
    public function index(InvoiceDatatableRequest $request): JsonResponse {
        $filters = $request->validated();
        $res     = $this->invoiceService->datatables($filters);
        return $this->successResponse($res, 'Invoices retrieved successfully.', 200);
    }
    public function store(InvoiceRequest $request) {
        $invoice = $this->invoiceService->create($request->validated());

        return response()->json([
            'data' => $invoice,
        ], 201);
    }

    public function show(Invoice $invoice) {

        return response()->json([
            'data' => $invoice,
        ], 200);
    }
}
