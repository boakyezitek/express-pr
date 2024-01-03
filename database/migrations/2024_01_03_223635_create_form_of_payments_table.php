<?php

use App\Models\FormOfPayment;
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
        Schema::create('form_of_payments', function (Blueprint $table) {
            $table->id();
            $table->string('form_of_payment');
            $table->tinyInteger('type');
            $table->timestamps();
        });


        FormOfPayment::create([
            'form_of_payment' => 'Personal Check',
            'type' => 1,
        ]);

        FormOfPayment::create([
            'form_of_payment' => 'Company Check',
            'type' => 1,
        ]);

        FormOfPayment::create([
            'form_of_payment' => 'Money Order',
            'type' => 1,
        ]);
        FormOfPayment::create([
            'form_of_payment' => 'Bank Check',
            'type' => 1,
        ]);
        FormOfPayment::create([
            'form_of_payment' => 'Bank Wire',
            'type' => 2,
        ]);
        FormOfPayment::create([
            'form_of_payment' => 'Swift',
            'type' => 2,
        ]);
        FormOfPayment::create([
            'form_of_payment' => 'ACH',
            'type' => 2,
        ]);
        FormOfPayment::create([
            'form_of_payment' => 'Zelle',
            'type' => 2,
        ]);
        FormOfPayment::create([
            'form_of_payment' => 'Venmo',
            'type' => 2,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_of_payments');
    }
};
