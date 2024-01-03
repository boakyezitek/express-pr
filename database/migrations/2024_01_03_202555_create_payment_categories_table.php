<?php

use App\Models\PaymentCategory;
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
        Schema::create('payment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('type_of_payment');
            $table->text('type_of_payment_description')->nullable();
            $table->timestamps();
        });

        PaymentCategory::create([
            'name' => 'Rent',
            'type_of_payment' => 1,
            'type_of_payment_description' => 'This is normal payment type',
        ]);

        PaymentCategory::create([
            'name' => 'Cleaning Fees',
            'type_of_payment' => 1,
            'type_of_payment_description' => 'This is normal payment type',
        ]);

        PaymentCategory::create([
            'name' => 'Other Fees',
            'type_of_payment' => 1,
            'type_of_payment_description' => 'This is normal payment type',
        ]);


        PaymentCategory::create([
            'name' => 'Security Deposit',
            'type_of_payment' => 1,
            'type_of_payment_description' => 'This is normal payment type',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_categories');
    }
};
