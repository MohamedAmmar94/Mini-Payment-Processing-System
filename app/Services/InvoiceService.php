<?php
namespace App\Services;
use App\Models\Invoice;
use DB;
use Illuminate\Support\Str;

class InvoiceService {
    /**
     * Create a new class instance.
     */
    public function __construct() {
        //
    }
    public function create(array $data): Invoice {
        return DB::transaction(function () use ($data) {

            $invoice = Invoice::create([
                'uuid'            => (string) Str::uuid(),
                'invoice_number'  => $this->generateInvoiceNumber(),
                'customer_id'     => $data['customer_id'],
                'issue_date'      => $data['issue_date'],
                'due_date'        => $data['due_date'] ?? null,
                'status'          => 'draft',
                'subtotal'        => 0,
                'tax_amount'      => 0,
                'discount_amount' => 0,
                'total'           => 0,
                'paid_amount'     => 0,
                'notes'           => $data['notes'] ?? null,
            ]);

            $subtotal       = 0;
            $taxAmount      = 0;
            $discountAmount = 0;

            foreach ($data['items'] as $item) {

                $quantity  = $item['quantity'];
                $unitPrice = $item['unit_price'];

                $discount = $item['discount_amount'] ?? 0;
                $tax      = $item['tax_amount'] ?? 0;

                $lineSubtotal = $quantity * $unitPrice;

                $lineTotal = $lineSubtotal
                     - $discount
                     + $tax;

                $invoice->items()->create([
                    'description'     => $item['description'],
                    'quantity'        => $quantity,
                    'unit_price'      => $unitPrice,
                    'discount_amount' => $discount,
                    'tax_amount'      => $tax,
                    'total'           => $lineTotal,
                ]);

                $subtotal       += $lineSubtotal;
                $discountAmount += $discount;
                $taxAmount      += $tax;
            }

            $total = $subtotal
                 - $discountAmount
                 + $taxAmount;

            $invoice->update([
                'subtotal'        => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount'      => $taxAmount,
                'total'           => $total,
                'status'          => 'issued',
            ]);

            return $invoice->load('items');
        });
    }

    private function generateInvoiceNumber(): string {
        return 'INV-' . now()->format('YmdHis') . rand(1, 100000);
    }
}
