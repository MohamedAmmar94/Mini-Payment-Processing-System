<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->restrictOnDelete();

            $table->decimal('amount', 20, 4);

            $table->string('currency', 3)->default('EGP');

            $table->string('status', 30)->default('pending');
            // pending
            // processing
            // paid
            // failed
            // refunded
            // cancelled
            $table->string('payment_method', 30);

            $table->string('provider', 50)->nullable();

            $table->string('provider_transaction_id')->nullable();

            $table->string('idempotency_key', 100)->unique();

            $table->jsonb('metadata')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'invoice_id',
                'status',
            ]);

            $table->index('provider_transaction_id');
        });
        DB::statement('
    ALTER TABLE payments
    ADD CONSTRAINT payments_amount_positive_check
    CHECK (amount > 0)
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
