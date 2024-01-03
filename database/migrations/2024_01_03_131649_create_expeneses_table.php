<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expeneses', function (Blueprint $table) {
            $table->id();
            $table->date('date_paid');
            $table->text('description');
            $table->integer('expense_amount');
            $table->foreignId('expenses_category_id')->index();
            $table->tinyInteger('is_reimbursement_necessary')->default(1);
            $table->date('reimbursement_date')->nullable();
            $table->foreignId('paid_by')->constrained('staff');
            $table->foreignId('paid_to')->constrained('vendor');
            $table->foreignId('property_id')->index();
            $table->foreignId('created_by')->constrained('staff');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeneses');
    }
};
