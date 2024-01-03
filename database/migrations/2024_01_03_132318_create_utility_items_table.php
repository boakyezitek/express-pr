<?php

use App\Models\UtilityItem;
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
        Schema::create('utility_items', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->timestamps();
        });

        UtilityItem::create(['value' => 'Gas']);
        UtilityItem::create(['value' => 'Water']);
        UtilityItem::create(['value' => 'Electricity']);
        UtilityItem::create(['value' => 'Internet']);
        UtilityItem::create(['value' => 'Cable TV']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_items');
    }
};
