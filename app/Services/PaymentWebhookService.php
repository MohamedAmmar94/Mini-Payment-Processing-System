<?php
namespace App\Services;
use App\Models\PaymentWebhookEvent;
use Yajra\DataTables\Facades\DataTables;

class PaymentWebhookService {
    /**
     * Create a new class instance.
     */
    public function __construct() {
        //
    }
    public function datatables($filters) {
        $query = PaymentWebhookEvent::query()
            ->orderByDesc('payment_webhook_events.created_at')
            ->orderByDesc('payment_webhook_events.id');
        return DataTables::eloquent($query)
            ->filter(function ($query) use ($filters) {
                if (! empty($filters['search_term'])) {
                    $term = $filters['search_term'];
                    $query->where(function ($q) use ($term) {
                        $q->where(
                            'event_type',
                            'ILIKE',
                            "%{$term}%"
                        )->orWhere(
                            'event_id',
                            'ILIKE',
                            "%{$term}%");
                    });
                }
                if (! empty($filters['from'])) {
                    $query->where(
                        'payments.created_at',
                        '>=',
                        $filters['from']
                    );
                }

                if (! empty($filters['to'])) {
                    $query->where(
                        'payments.created_at',
                        '<',
                        $filters['to']
                    );
                }
            })
            ->toJson();
    }

}
