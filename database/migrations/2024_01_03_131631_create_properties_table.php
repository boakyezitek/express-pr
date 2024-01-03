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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_name');
            $table->text('description');
            $table->integer('monthly_rent_wanted');
            $table->integer('min_security_deposit');
            $table->integer('min_lease_term');
            $table->integer('max_lease_term');
            $table->tinyInteger('bedroom');
            $table->tinyInteger('bath_full');
            $table->tinyInteger('bath_half');
            $table->integer('size');
            $table->string('street_address');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->tinyInteger('furnished')->default(1);
            $table->tinyInteger('featured');
            $table->tinyInteger('parking_spaces')->default(1);
            $table->string('parking_number')->nullable();
            $table->integer('parking_fees')->nullable();
            $table->tinyInteger('is_lease_the_start_date_and_end_date')->default(1);
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->date('available_property_date');
            $table->decimal('lat', 11, 8);
            $table->decimal('lng', 11, 8);
            $table->tinyInteger('status')->default(1);
            $table->foreignId('created_by')->constrained('staff');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('property_amenity', function(Blueprint $table) {
            $table->foreignId('property_id')->index();
            $table->foreignId('amenity_item_id')->index();

            $table->unique(['property_id', 'amenity_item_id']);
        });

        Schema::create('property_utility_included', function(Blueprint $table) {
            $table->foreignId('property_id')->index();
            $table->foreignId('utility_item_id')->index();

            $table->unique(['property_id', 'utility_item_id']);
        });

        Schema::create('property_type', function(Blueprint $table) {
            $table->foreignId('property_id')->index();
            $table->foreignId('property_type_item_id')->index();

            $table->unique(['property_id', 'property_type_item_id']);
        });

        Schema::create('property_parking_type', function(Blueprint $table) {
            $table->foreignId('property_id')->index();
            $table->foreignId('parking_item_id')->index();

            $table->unique(['property_id', 'parking_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
