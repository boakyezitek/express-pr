<?php

namespace Tests\Feature;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StaffTest extends TestCase
{
    use LazilyRefreshDatabase;
    /**
     * @test
     */
    public function test_application_can_list_all_staff_in_paginated_manner(): void
    {
        $user = User::factory()->create();

        Staff::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'staff_type' => Staff::ADMIN
        ]);

        $this->actingAs($user);

        $users = User::factory(5)->create();

        foreach($users as $user) {
            Staff::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'staff_type' => Staff::ADMIN
            ]);
        }

        $response = $this->get('/api/staff');
        // $response->dd();
        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(6, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));

    }

    /**
     * @test
     */
    public function test_application_can_list_all_manager_in_paginated_manner(): void
    {
        $user = User::factory()->create();

        Staff::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'staff_type' => Staff::ADMIN
        ]);

        $this->actingAs($user);

        $users = User::factory(5)->create();

        foreach($users as $user) {
            Staff::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'staff_type' => Staff::MANAGER
            ]);
        }

        $response = $this->get('/api/staff?staff_type='.Staff::MANAGER);
        // $response->dd();
        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(5, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));

    }

    /**
     * @test
     */
    public function test_application_can_list_all_staff_member_in_paginated_manner(): void
    {
        $user = User::factory()->create();

        Staff::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'staff_type' => Staff::ADMIN
        ]);

        $this->actingAs($user);

        $users = User::factory(3)->create();

        foreach ($users as $user) {
            Staff::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'staff_type' => Staff::STAFF
            ]);
        }

        $response = $this->get('/api/staff?staff_type=' . Staff::STAFF);
        // $response->dd();
        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(3, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }


    /**
     * @test
     */
    public function test_application_can_list_all_staff_visible_on_website(): void
    {

        $users = User::factory(6)->create();

        foreach ($users as $user) {
            Staff::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'staff_type' => Staff::STAFF,
                'is_visible_on_website' => true,
            ]);
        }

        $users = User::factory(3)->create();

        foreach ($users as $user) {
            Staff::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'staff_type' => Staff::STAFF,
                'is_visible_on_website' => false,
            ]);
        }

        $response = $this->get('/api/staff/visible-on-website');
        // $response->dd();
        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(6, 'data');


        $this->assertNotNull($response->json('data'));
    }

    /**
     * @test
     */
    public function test_application_can_list_all_staff_with_avatar(): void
    {
        $user = User::factory()->create();

        Staff::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'staff_type' => Staff::ADMIN
        ]);

        $this->actingAs($user);

        $users = User::factory(2)->create();

        foreach ($users as $user) {
            $staff = Staff::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'staff_type' => Staff::ADMIN
            ]);

            $staff->avatar()->create(['path' => 'avatar.png']);
        }

        $response = $this->get('/api/staff');
        // $response->dd();
        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(3, 'data');


        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    /**
     * @test
     *
    */

    public function test_application_can_show_single_staff_with_avatar_and_account()
    {
        $user = User::factory()->create();
        Staff::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'staff_type' => Staff::ADMIN
        ]);

        $this->actingAs($user);

        $manager = User::factory()->create();

        $staff = Staff::factory()->create([
            'user_id' => $manager->id,
            'email' => $user->email,
            'staff_type' => Staff::MANAGER
        ]);

        $staff->avatar()->create([
            'path' => 'avatar.png',
        ]);

        $response = $this->get('/api/staff/'.$staff->id);
        $response->assertOk();

    }

    /**
     * @test
    */

    public function test_application_can_create_new_staff()
    {
        $user = User::factory()->create();
        Staff::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'staff_type' => Staff::ADMIN
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/staff', [
            'first_name' => 'Kofi',
            'last_name' => 'Boakye',
            'email' => 'testing@gmail.com',
            'phone' => '+1-618-378-8086',
            'staff_type' => Staff::MANAGER,
        ]);

        $response->assertOk()
        ->assertJsonPath('data.first_name', 'Kofi')
        ->assertJsonPath('data.last_name', 'Boakye')
        ->assertJsonPath('data.email', 'testing@gmail.com')
        ->assertJsonPath('data.phone', '+1-618-378-8086')
        ->assertJsonPath('data.staff_type', Staff::MANAGER);

        // $this->assertDatabaseHas('staff', [
        //     'email' => 'testing@gmail.com',
        // ]);
    }


    /**
     * @test
     *
     * */

    public function test_application_can_update_staff()
    {
        $user = User::factory()->create();
        Staff::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'staff_type' => Staff::ADMIN
        ]);

        $this->actingAs($user);

        $manager = User::factory()->create();

        $staff = Staff::factory()->create([
            'user_id' => $manager->id,
            'email' => $manager->email,
            'staff_type' => Staff::MANAGER
        ]);


        $response = $this->putJson('/api/staff/'.$staff->id, [
            'first_name' => 'Abigail',
            'last_name' => 'Yeboah',
            'email' => 'abigailyeboah@gmail.com',
        ]);

        $response->assertOk()
        ->assertJsonPath('data.first_name', 'Abigail')
        ->assertJsonPath('data.last_name', 'Yeboah')
        ->assertJsonPath('data.email', 'abigailyeboah@gmail.com');

    }


        /**
     * @test
     *
     * */

     public function test_application_can_delete_staff()
     {
         Storage::put('/avatar.png', 'empty');

         $user = User::factory()->create();
         Staff::factory()->create([
             'user_id' => $user->id,
             'email' => $user->email,
             'staff_type' => Staff::ADMIN
         ]);

         $this->actingAs($user);

         $manager = User::factory()->create();

         $staff = Staff::factory()->create([
             'user_id' => $manager->id,
             'email' => $manager->email,
             'staff_type' => Staff::MANAGER
         ]);

         $avatar = $staff->avatar()->create([ 'path'=> 'avatar.png']);

         $response = $this->deleteJson('/api/staff/'.$staff->id);

         $response->assertOk();

         $this->assertSoftDeleted($staff);
         $this->assertModelMissing($avatar);

         Storage::assertMissing('avatar.png');



     }

}
