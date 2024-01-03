<?php

use App\Models\ExpensesCategory;
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
        Schema::create('expenses_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('type_of_expenses');
            $table->timestamps();
        });

        ExpensesCategory::create([
            'name' => 'Maintenance',
            'type_of_expenses' => 1,
        ]);

        ExpensesCategory::create([
            'name' => 'Cleaning',
            'type_of_expenses' => 1,
        ]);

        ExpensesCategory::create([
            'name' => 'Renovation',
            'type_of_expenses' => 1,
        ]);

        ExpensesCategory::create([
            'name' => 'Furnishings',
            'type_of_expenses' => 1,
        ]);

        ExpensesCategory::create([
            'name' => 'Registration',
            'type_of_expenses' => 1,
        ]);

        ExpensesCategory::create([
            'name' => 'Supplies',
            'type_of_expenses' => 1,
        ]);

        ExpensesCategory::create([
            'name' => 'Escrew',
            'type_of_expenses' => 2,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_categories');
    }
};
