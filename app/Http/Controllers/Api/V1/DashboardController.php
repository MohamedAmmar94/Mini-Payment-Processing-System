<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {
    public function summary() {
        $customersCount = DB::table('customers')
            ->whereNull('deleted_at')
            ->count();

        $invoiceStats = DB::table('invoices')
            ->whereNull('deleted_at')
            ->selectRaw("
        COUNT(*) AS invoices_count,
        COUNT(CASE WHEN status = 'paid' THEN 1 END) AS paid_invoices_count,
        COUNT(CASE WHEN status IN ('issued', 'partially_paid', 'overdue')
            THEN 1 END) AS pending_invoices_count
    ")
            ->first();

        $invoicesCount        = $invoiceStats->invoices_count;
        $paidInvoicesCount    = $invoiceStats->paid_invoices_count;
        $pendingInvoicesCount = $invoiceStats->pending_invoices_count;

        $paymentStats = DB::table('payments')
            ->whereNull('deleted_at')
            ->selectRaw(
                "COUNT(*) as payments_count,
                COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid_payments_count,
                COUNT(CASE WHEN status = 'failed' THEN 1 END) as failed_payments_count"
            )
            ->first();

        $paymentsCount       = $paymentStats->payments_count;
        $paidPaymentsCount   = $paymentStats->paid_payments_count;
        $failedPaymentsCount = $paymentStats->failed_payments_count;

        $paidAmountsByCurrency = DB::table('payments')
            ->where('status', 'paid')
            ->select('currency')
            ->selectRaw('SUM(amount) AS total')
            ->groupBy('currency')
            ->orderBy('currency')
            ->get();

        return response()->json([
            'error'   => false,
            'message' => 'Dashboard summary retrieved successfully.',
            'data'    => [
                'customers_count'          => $customersCount,
                'invoices_count'           => $invoicesCount,
                'paid_invoices_count'      => $paidInvoicesCount,
                'pending_invoices_count'   => $pendingInvoicesCount,
                'payments_count'           => $paymentsCount,
                'paid_payments_count'      => $paidPaymentsCount,
                'failed_payments_count'    => $failedPaymentsCount,
                'paid_amounts_by_currency' => $paidAmountsByCurrency,
            ],
        ]);
    }
}
