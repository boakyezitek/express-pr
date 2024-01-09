<?php

namespace Tests\Feature;

use App\Models\Staff;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use LazilyRefreshDatabase;
    /**
     * @test
     */
    public function test_application_can_list_all_vendors_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);


        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $vendor = Vendor::factory()->create(['user_id' => $user->id]);

            $vendor->avatar()->create(['path' => 'avatar.png']);
            $vendor->pictureId()->create(['path' => 'photoId.png']);
        }

        $response = $this->get('/api/vendors');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(10, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_single_vendor(): void
    {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);

        $vendor->avatar()->create(['path' => 'avatar.png']);
        $vendor->pictureId()->create(['path' => 'photoId.png']);

        $response = $this->get('/api/vendors/'.$vendor->id);

        $response->assertOk();
    }

     public function test_application_can_create_vendor(): void
     {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);


        $response = $this->postJson('/api/vendors/', [
            'first_name' => 'Kofi',
            'last_name' => 'Boakye',
            'email' => 'testtenant@gmail.com',
            'phone' => '+1-618-378-8086',
            'address' => 'Suite 958',
            'city' => 'Birdiebury',
            'state' => 'Connecticut',
            'zipcode' => '80459-7217',
        ]);

        $response->assertCreated()
        ->assertJsonPath('data.first_name', 'Kofi')
        ->assertJsonPath('data.last_name', 'Boakye')
        ->assertJsonPath('data.email', 'testtenant@gmail.com')
        ->assertJsonPath('data.phone', '+1-618-378-8086')
        ->assertJsonPath('data.address', 'Suite 958')
        ->assertJsonPath('data.city', 'Birdiebury')
        ->assertJsonPath('data.state', 'Connecticut')
        ->assertJsonPath('data.zipcode', '80459-7217');
     }

    public function test_application_can_update_vendor(): void
    {
       $user = User::factory()->create();
       Staff::factory()->create(['user_id' => $user->id]);

       $this->actingAs($user);

       User::factory()->create();
       $tenant = Vendor::factory()->create(['user_id' => $user->id]);

       $tenant->avatar()->create(['path' => 'avatar.png']);
       $tenant->pictureId()->create(['path' => 'photoId.png']);

       $response = $this->putJson('/api/vendors/'.$tenant->id, [
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


   public function test_application_can_delete_vendor(): void
   {
       Storage::put('/avatar.png', 'empty');
       Storage::put('/photoId.png', 'empty');

       $user = User::factory()->create();
       Staff::factory()->create(['user_id' => $user->id]);

       $this->actingAs($user);

       User::factory()->create();
       $vendor = Vendor::factory()->create(['user_id' => $user->id]);

       $avatar = $vendor->avatar()->create(['path' => 'avatar.png']);
       $idPicture = $vendor->pictureId()->create(['path' => 'photoId.png']);

       $response = $this->deleteJson('/api/vendors/'.$vendor->id);

       $response->assertOk();

       $this->assertSoftDeleted($vendor);

       $this->assertModelMissing($avatar);
       $this->assertModelMissing($idPicture);

       Storage::assertMissing('avatar.png');
       Storage::assertMissing('photoId.png');
   }
}
