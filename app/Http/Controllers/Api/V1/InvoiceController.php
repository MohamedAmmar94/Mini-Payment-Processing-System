<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InvoiceRequest;
use App\Services\InvoiceService;

class InvoiceController extends Controller {
    public function __construct(protected InvoiceService $invoiceService) {
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
