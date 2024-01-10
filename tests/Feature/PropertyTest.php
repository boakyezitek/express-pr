<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Property;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use LazilyRefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_application_can_get_all_properties_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        Property::factory(5)->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        $response = $this->get('/api/properties');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(5, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_get_all_properties_with_relation_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $properties = Property::factory(5)->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        foreach($properties as $property){
            $property->propertyAmenity()->attach([3, 2, 1]);
            $property->propertyUtilityIncluded()->attach([1, 3, 2]);
            $property->propertyType()->attach([3]);
            $property->propertyParkingType()->attach([2, 4]);

            $property->images()->createMany([
                ['path' => 'propertyPic1.png'],
                ['path' => 'propertyPic2.png'],
                ['path' => 'propertyPic3.png'],
                ['path' => 'propertyPic4.png'],
                ['path' => 'propertyPic5.png'],
                ['path' => 'propertyPic6.png'],
            ]);
        }

        $response = $this->get('/api/properties');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(5, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_get_all_properties_now_month_year_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);
        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-12-01'
        ]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);
        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2024-01-01'
        ]);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);
        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2024-02-01'
        ]);

        $response = $this->get('/api/properties?month_year=2024-01');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(9, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_get_all_properties_next_month_year_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);
        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-12-01'
        ]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);
        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2024-01-01'
        ]);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);
        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2024-02-01'
        ]);

        $response = $this->get('/api/properties?month_year=2024-02');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(3, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_get_all_properties_with_available_property_date_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);
        Property::factory(4)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-12-01'
        ]);

        $response = $this->get('/api/properties?available_property_date=2023-12-01');

        $response->assertOk();

        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(4, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_get_all_properties_with_client_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $cliet_2_user = User::factory()->create();
        $client_2 = Client::factory()->create(['user_id' => $cliet_2_user->id]);
        Property::factory(4)->create([
            'client_id' => $client_2->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-12-01'
        ]);

        $response = $this->get('/api/properties?client='.$client_2->id);

        $response->assertOk();

        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(4, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_get_all_properties_with_status_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        Property::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $cliet_2_user = User::factory()->create();
        $client_2 = Client::factory()->create(['user_id' => $cliet_2_user->id]);
        Property::factory(4)->create([
            'client_id' => $client_2->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-12-01',
            'status' => Property::PROPERTY_MAINTENANCE,
        ]);

        Property::factory(5)->create([
            'client_id' => $client_2->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-12-01',
            'status' => Property::PROPERTY_RENTED,
        ]);

        Property::factory(2)->create([
            'client_id' => $client_2->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-12-01',
            'status' => Property::PROPERTY_CANCELLED,
        ]);



        $response = $this->get('/api/properties?status=4');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(2, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }


    public function test_application_can_get_all_properties_with_property_type_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property_1 = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $property_1->propertyType()->attach([6]);

        $property_2 = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);
        $property_2->propertyType()->attach([5]);

        $property_3 = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);
        $property_3->propertyType()->attach([7]);

        $property_4 = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);
        $property_4->propertyType()->attach([5]);

        $property_5 = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);
        $property_5->propertyType()->attach([3]);


        $response = $this->get('/api/properties?property_type=5');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(2, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }


    public function test_application_can_get_all_properties_with_search_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        Property::factory(5)->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);


        $response = $this->get('/api/properties?search=ab');

        $response->assertOk();

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }


    public function test_application_can_get_single_property_with_relationship(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $property->propertyAmenity()->attach([3, 2, 1]);
        $property->propertyUtilityIncluded()->attach([1, 3, 2]);
        $property->propertyType()->attach([3]);
        $property->propertyParkingType()->attach([2, 4]);

        $property->images()->createMany([
            ['path' => 'propertyPic1.png'],
            ['path' => 'propertyPic2.png'],
            ['path' => 'propertyPic3.png'],
            ['path' => 'propertyPic4.png'],
            ['path' => 'propertyPic5.png'],
            ['path' => 'propertyPic6.png'],
        ]);

        $response = $this->get('/api/properties/'.$property->id);

        $response->assertOk();

    }


    public function test_application_can_create_property(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $response = $this->postJson('/api/properties', [
            "property_name" => "iusto ipsum est",
            "description" => "Fugiat in commodi sequi. Aperiam eos itaque iste ut alias et. Esse voluptatibus atque tenetur omnis minus. Tempore cupiditate qui magni vitae dignissimos facilis.",
            "monthly_rent_wanted" => 5372,
            "min_security_deposit" => 825,
            "min_lease_term" => 7,
            "max_lease_term" => 10,
            "bedroom" => 1,
            "bath_full" => 8,
            "bath_half" => 7,
            "size" => 4160,
            "street_address" => "Apt. 784",
            "city" => "Paulineland",
            "state" => "Illinois",
            "zipcode" => "42494",
            "furnished" => 2,
            "featured" => 1,
            "parking_spaces" => 2,
            "parking_number" => "parking-6029",
            "parking_fees" => 7,
            "is_lease_the_start_date_and_end_date" => 2,
            "lease_start_date" => "2024-01-14",
            "lease_end_date" => "2025-01-14",
            "available_property_date" => "2024-01-01",
            "lat" => "-38.83158800",
            "lng" => "28.64999600",
            "status" => 1,
            "client_id" => $client->id,
            "created_by" => $staff->id,
            "property_amenity" => [1, 2, 3, ],
            "property_utility_included" => [2, 3, 4],
            "property_type" => [4],
            "property_parking_type" => [1, 2, 3],
        ]);

        $response->assertCreated()
        ->assertJsonPath('data.property_name', 'iusto ipsum est')
        ->assertJsonPath('data.monthly_rent_wanted', 5372)
        ->assertJsonPath('data.min_security_deposit', 825)
        ->assertJsonPath('data.min_lease_term', 7)
        ->assertJsonPath('data.max_lease_term', 10)
        ->assertJsonPath('data.available_property_date', '2024-01-01');

    }

    public function test_application_can_update_property(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $property->propertyAmenity()->attach([1, 2]);
        $property->propertyUtilityIncluded()->attach([1, 3]);
        $property->propertyType()->attach([1]);
        $property->propertyParkingType()->attach([1, 2]);

        $property->images()->createMany([
            ['path' => 'propertyPic1.png'],
            ['path' => 'propertyPic2.png'],
        ]);

        $response = $this->putJson('/api/properties/'.$property->id, [
            "property_name" => "iusto ipsum est",
            "description" => "Fugiat in commodi sequi. Aperiam eos itaque iste ut alias et. Esse voluptatibus atque tenetur omnis minus. Tempore cupiditate qui magni vitae dignissimos facilis.",
            "monthly_rent_wanted" => 5372,
            "min_security_deposit" => 825,
            "min_lease_term" => 7,
            "max_lease_term" => 10,
            "available_property_date" => "2024-03-01",
            "property_amenity" => [3, 4],
            "property_utility_included" => [2, 4],
            "property_type" => [3],
            "property_parking_type" => [3, 4],
        ]);

        $response->assertOk()
        ->assertJsonPath('data.property_name', 'iusto ipsum est')
        ->assertJsonPath('data.monthly_rent_wanted', 5372)
        ->assertJsonPath('data.min_security_deposit', 825)
        ->assertJsonPath('data.min_lease_term', 7)
        ->assertJsonPath('data.max_lease_term', 10)
        ->assertJsonPath('data.available_property_date', '2024-03-01');

    }

    public function test_application_can_delete_property(): void
    {
        Storage::put('/propertyPic1.png', 'empty');
        Storage::put('/propertyPic2.png', 'empty');

        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create([
            'client_id' => $client->id,
            'created_by' => $staff->id,
            'available_property_date' => '2023-11-01'
        ]);

        $property->propertyAmenity()->attach([1, 2]);
        $property->propertyUtilityIncluded()->attach([1, 3]);
        $property->propertyType()->attach([1]);
        $property->propertyParkingType()->attach([1, 2]);

        $images = $property->images()->createMany([
            ['path' => 'propertyPic1.png'],
            ['path' => 'propertyPic2.png'],
        ]);

        $response = $this->deleteJson('/api/properties/'.$property->id);

        $response->assertOk();

        $this->assertSoftDeleted($property);
        foreach ($images as $image) {
            $this->assertModelMissing($image);
        }

        Storage::assertMissing('propertyPic1.png');
        Storage::assertMissing('propertyPic2.png');
    }
}
