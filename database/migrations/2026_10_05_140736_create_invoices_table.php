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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('invoice_number', 50)->unique();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            $table->date('issue_date');

            $table->date('due_date')->nullable();

            $table->string('status', 30)->default('draft');

            // draft
            // issued
            // partially_paid
            // paid
            // cancelled
            // overdue
            $table->decimal('subtotal', 20, 4)->default(0);

            $table->decimal('tax_amount', 20, 4)->default(0);

            $table->decimal('discount_amount', 20, 4)->default(0);

            $table->decimal('total', 20, 4)->default(0);

            $table->decimal('paid_amount', 20, 4)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'customer_id',
                'status',
            ]);

            $table->index('issue_date');
            $table->index('due_date');
        });
        DB::statement('
    ALTER TABLE invoices
    ADD CONSTRAINT invoices_amounts_non_negative_check
    CHECK (
        subtotal >= 0
        AND tax_amount >= 0
        AND discount_amount >= 0
        AND total >= 0
        AND paid_amount >= 0
    )
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};
