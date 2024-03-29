<?php

use App\Models\ParkingItem;
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
        Schema::create('parking_items', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->timestamps();
        });

        ParkingItem::create(['value' => 'Garage']);
        ParkingItem::create(['value' => 'Parking Lot']);
        ParkingItem::create(['value' => 'Car Port']);
        ParkingItem::create(['value' => 'Driveway']);
        ParkingItem::create(['value' => 'Street Parking']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_items');
    }
};
