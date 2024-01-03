<?php

use App\Models\PropertyTypeItem;
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
        Schema::create('property_type_items', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->timestamps();
        });

        PropertyTypeItem::create(['value' => 'Single Family House']);
        PropertyTypeItem::create(['value' => 'Townhouse']);
        PropertyTypeItem::create(['value' => 'Condo/Coop']);
        PropertyTypeItem::create(['value' => 'Duplex']);
        PropertyTypeItem::create(['value' => 'Triplex']);
        PropertyTypeItem::create(['value' => 'Quadplex']);
        PropertyTypeItem::create(['value' => 'Multi']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_type_items');
    }
};
