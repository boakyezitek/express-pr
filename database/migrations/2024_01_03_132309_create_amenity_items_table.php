<?php

use App\Models\AmenityItem;
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
        Schema::create('amenity_items', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->timestamps();
        });

        AmenityItem::create(['value' => 'Gym']);
        AmenityItem::create(['value' => 'Pool']);
        AmenityItem::create(['value' => 'Playground']);
        AmenityItem::create(['value' => 'Business center']);
        AmenityItem::create(['value' => 'Party Room']);
        AmenityItem::create(['value' => 'Dog Park']);
        AmenityItem::create(['value' => 'Walking Trail']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_items');
    }
};
