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
use Illuminate\Support\Testing\Fakes\Fake;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */

    public function test_application_can_load_all_clients_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $client = Client::factory()->create(['user_id' => $user->id]);

            $client->avatar()->create(['path' => 'avatar.png']);
            $client->pictureId()->create(['path' => 'photoId.png']);
        }

        $response = $this->get('/api/clients');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(10, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    /**
     * @test
     */

     public function test_application_can_load_single_client(): void
     {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $client->avatar()->create(['path' => 'avatar.png']);
        $client->pictureId()->create(['path' => 'photoId.png']);

        $response = $this->get('/api/clients/'.$client->id);

        $response->assertOk();
     }

    /**
     * @test
    */
     public function test_application_can_load_single_client_with_property_count(): void
     {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $client->avatar()->create(['path' => 'avatar.png']);
        $client->pictureId()->create(['path' => 'photoId.png']);

        Property::factory(5)->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        Property::factory(2)->create([
            'client_id' => $client->id,
             'created_by' => $staff->id,
             'status' => Property::PROPERTY_RENTED
        ]);

        Property::factory(3)->create([
            'client_id' => $client->id,
             'created_by' => $staff->id,
             'status' => Property::PROPERTY_MAINTENANCE
        ]);

        Property::factory(2)->create([
            'client_id' => $client->id,
             'created_by' => $staff->id,
             'status' => Property::PROPERTY_CANCELLED
        ]);

        $response = $this->get('/api/clients/'.$client->id);

        $response->assertOk();
     }

    /**
     * @test
     */

     public function test_application_can_create_client(): void
     {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);


        $response = $this->postJson('/api/clients/', [
            'first_name' => 'Kofi',
            'last_name' => 'Boakye',
            'email' => 'testclient@gmail.com',
            'phone' => '+1-618-378-8086',
            'address' => 'Suite 958',
            'city' => 'Birdiebury',
            'state' => 'Connecticut',
            'zipcode' => '80459-7217',
        ]);

        $response->assertCreated()
        ->assertJsonPath('data.first_name', 'Kofi')
        ->assertJsonPath('data.last_name', 'Boakye')
        ->assertJsonPath('data.email', 'testclient@gmail.com')
        ->assertJsonPath('data.phone', '+1-618-378-8086')
        ->assertJsonPath('data.address', 'Suite 958')
        ->assertJsonPath('data.city', 'Birdiebury')
        ->assertJsonPath('data.state', 'Connecticut')
        ->assertJsonPath('data.zipcode', '80459-7217');
     }

    /**
     * @test
    */
     public function test_application_can_update_client(): void
     {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $client->avatar()->create(['path' => 'avatar.png']);
        $client->pictureId()->create(['path' => 'photoId.png']);

        $response = $this->putJson('/api/clients/'.$client->id, [
            'first_name' => 'Kofi',
            'city' => 'Birdiebury',
            'state' => 'Connecticut',
            'zipcode' => '80459-7217',
        ]);

        $response->assertOk()
        ->assertJsonPath('data.first_name', 'Kofi')
        ->assertJsonPath('data.city', 'Birdiebury')
        ->assertJsonPath('data.state', 'Connecticut')
        ->assertJsonPath('data.zipcode', '80459-7217');
     }

    /**
     * @test
    */

    public function test_application_can_delete_client(): void
    {
        Storage::put('/avatar.png', 'empty');
        Storage::put('/photoId.png', 'empty');

        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $avatar = $client->avatar()->create(['path' => 'avatar.png']);
        $idPicture = $client->pictureId()->create(['path' => 'photoId.png']);

        $response = $this->deleteJson('/api/clients/'.$client->id);

        $response->assertOk();

        $this->assertSoftDeleted($client);

        $this->assertModelMissing($avatar);
        $this->assertModelMissing($idPicture);

        Storage::assertMissing('avatar.png');
        Storage::assertMissing('photoId.png');
    }
}
