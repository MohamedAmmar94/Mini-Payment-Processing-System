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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnDelete();

            $table->string('description');

            $table->decimal('quantity', 20, 4)->default(1);

            $table->decimal('unit_price', 20, 4);

            $table->decimal('discount_amount', 20, 4)->default(0);

            $table->decimal('tax_amount', 20, 4)->default(0);

            $table->decimal('total', 20, 4);

            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
        });
        DB::statement('
    ALTER TABLE invoice_items
    ADD CONSTRAINT invoice_items_quantity_positive_check
    CHECK (quantity > 0)
');

        DB::statement('
    ALTER TABLE invoice_items
    ADD CONSTRAINT invoice_items_unit_price_positive_check
    CHECK (unit_price >= 0)
');

        DB::statement('
    ALTER TABLE invoice_items
    ADD CONSTRAINT invoice_items_total_positive_check
    CHECK (total >= 0)
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('invoice_items');
    }
};
