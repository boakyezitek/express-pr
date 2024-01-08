<?php

namespace Tests\Feature;

use App\Models\Staff;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use LazilyRefreshDatabase;
    /**
     * @test
     */
    public function test_application_can_list_all_tenants_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);


        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $tenant = Tenant::factory()->create(['user_id' => $user->id]);

            $tenant->avatar()->create(['path' => 'avatar.png']);
            $tenant->pictureId()->create(['path' => 'photoId.png']);
        }

        $response = $this->get('/api/tenants');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(10, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    /**
     *
     *@test
    */

    public function test_application_can_load_single_tenant()
    {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $user->id]);

        $tenant->avatar()->create(['path' => 'avatar.png']);
        $tenant->pictureId()->create(['path' => 'photoId.png']);

        $response = $this->get('/api/tenants/'.$tenant->id);

        $response->assertOk();
    }


        /**
     * @test
     */

     public function test_application_can_create_tenant(): void
     {
        $user = User::factory()->create();
        Staff::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);


        $response = $this->postJson('/api/tenants/', [
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



    /**
     * @test
    */
    public function test_application_can_update_tenant(): void
    {
       $user = User::factory()->create();
       Staff::factory()->create(['user_id' => $user->id]);

       $this->actingAs($user);

       User::factory()->create();
       $tenant = Tenant::factory()->create(['user_id' => $user->id]);

       $tenant->avatar()->create(['path' => 'avatar.png']);
       $tenant->pictureId()->create(['path' => 'photoId.png']);

       $response = $this->putJson('/api/tenants/'.$tenant->id, [
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

   public function test_application_can_delete_tenant(): void
   {
       Storage::put('/avatar.png', 'empty');
       Storage::put('/photoId.png', 'empty');

       $user = User::factory()->create();
       Staff::factory()->create(['user_id' => $user->id]);

       $this->actingAs($user);

       User::factory()->create();
       $tenant = Tenant::factory()->create(['user_id' => $user->id]);

       $avatar = $tenant->avatar()->create(['path' => 'avatar.png']);
       $idPicture = $tenant->pictureId()->create(['path' => 'photoId.png']);

       $response = $this->deleteJson('/api/tenants/'.$tenant->id);

       $response->assertOk();

       $this->assertSoftDeleted($tenant);

       $this->assertModelMissing($avatar);
       $this->assertModelMissing($idPicture);

       Storage::assertMissing('avatar.png');
       Storage::assertMissing('photoId.png');
   }
}
