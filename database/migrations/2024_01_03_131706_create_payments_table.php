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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->integer('payment_amount');
            $table->text('memo');
            $table->string('payment_form_number');
            $table->date('date_deposited');
            $table->date('date_confirmed');
            $table->foreignId('confirmed_by')->constrained('staff');
            $table->foreignId('tenant_id')->index();
            $table->foreignId('property_id')->index();
            $table->foreignId('created_by')->constrained('staff');
            $table->foreignId('form_of_payment_id')->index();
            $table->date('payment_received')->nullable();
            $table->morphs('depositable');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
