<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Staff;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_application_can_load_payment_in_pagination_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        // paid to vendor
        $staffUser2 = User::factory()->create();
        $staff2 = Staff::factory()->create(['user_id' => $staffUser2->id, 'staff_type' => Staff::MANAGER]);

        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $payment = $staff2->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $payment->proofOfPayment()->create(['path' => 'proofOfPayment.png']);

        $response = $this->get('/api/payments');

        $response->assertOk();

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(2, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }


    public function test_application_can_load_payment_with_payment_date_in_pagination_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        // paid to vendor
        $staffUser2 = User::factory()->create();
        $staff2 = Staff::factory()->create(['user_id' => $staffUser2->id, 'staff_type' => Staff::MANAGER]);

        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => '2024-02-10',
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $payment = $staff2->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $response = $this->get('/api/payments?payment_date=2024-02-10');

        $response->assertOk();

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(1, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_payment_with_payment_received_in_pagination_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        // paid to vendor
        $staffUser2 = User::factory()->create();
        $staff2 = Staff::factory()->create(['user_id' => $staffUser2->id, 'staff_type' => Staff::MANAGER]);

        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $payment = $staff2->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => '2024-02-10',
        ]);

        $response = $this->get('/api/payments?payment_received=2024-02-10');

        $response->assertOk();

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(1, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_payment_with_tenant_in_pagination_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        // paid to vendor
        $staffUser2 = User::factory()->create();
        $staff2 = Staff::factory()->create(['user_id' => $staffUser2->id, 'staff_type' => Staff::MANAGER]);

        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $payment = $staff2->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => '2024-02-10',
        ]);

        $response = $this->get('/api/payments?tenant_id='.$tenant->id);

        $response->assertOk();

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(2, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_payment_with_property_in_pagination_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        // paid to vendor
        $staffUser2 = User::factory()->create();
        $staff2 = Staff::factory()->create(['user_id' => $staffUser2->id, 'staff_type' => Staff::MANAGER]);

        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $payment = $staff2->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => '2024-02-10',
        ]);

        $response = $this->get('/api/payments?property_id='.$property->id);

        $response->assertOk();

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(2, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_payment_with_confirmed_by_in_pagination_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        // paid to vendor
        $staffUser2 = User::factory()->create();
        $staff2 = Staff::factory()->create(['user_id' => $staffUser2->id, 'staff_type' => Staff::MANAGER]);

        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $staff2->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => '2024-02-10',
        ]);

        $response = $this->get('/api/payments?confirmed_by='.$staff->id);

        $response->assertOk();

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id']]]);
        $response->assertJsonCount(2, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }


    public function test_application_can_load_single_payment(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);


        $response = $this->get('/api/payments/'.$payment->id);

        $response->assertOk();
    }

    public function test_application_can_create_payment_as_deposited_by_staff(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        $response = $this->postJson('/api/payments/', [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
            'deposite_by_type' => 'staff',
            'deposite_by' => $staff->id,
        ]);

        $response->assertCreated()
        ->assertJsonPath('data.property_id', $property->id)
        ->assertJsonPath('data.tenant_id', $tenant->id)
        ->assertJsonPath('data.confirmed_by.id', $staff->id)
        ->assertCreated();
    }

    public function test_application_can_create_payment_as_deposited_by_tenant(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);


        $response = $this->postJson('/api/payments/', [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
            'deposite_by_type' => 'tenant',
            'deposite_by' => $tenant->id,
        ]);

        $response
        ->assertJsonPath('data.property_id', $property->id)
        ->assertJsonPath('data.tenant_id', $tenant->id)
        ->assertJsonPath('data.confirmed_by.id', $staff->id)
        ->assertCreated();
    }


    public function test_application_can_update_payment_as_deposited_by_tenant(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $payment->proofOfPayment()->create(['path' => 'proofOfPayment.png']);

        $response = $this->putJson('/api/payments/'.$payment->id, [
            'form_of_payment_id' => 1,
            'payment_amount' => 200,
            'memo' => $this->faker->paragraph(),
            'deposite_by_type' => 'staff',
            'deposite_by' => $staff->id,
        ]);


        $response
        ->assertJsonPath('data.property_id', $property->id)
        ->assertJsonPath('data.tenant_id', $tenant->id)
        ->assertJsonPath('data.confirmed_by.id', $staff->id)
        ->assertOk();
    }

    public function test_application_can_delete_payment(): void
    {
        Storage::put('/proofOfPayment.png', 'empty');
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $tenant_user = User::factory()->create();
        $tenant = Tenant::factory()->create(['user_id' => $tenant_user->id]);

        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);



        $payment = $tenant->payment()->create(
            [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'form_of_payment_id' => 1,
            'confirmed_by' => $staff->id,
            'created_by' => $staff->id,
            'payment_date' => now()->addDay(15)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ]);

        $proofOfPayment = $payment->proofOfPayment()->create(['path' => 'proofOfPayment.png']);

        $response = $this->deleteJson('/api/payments/'.$payment->id);

        $response->assertOk();

        $this->assertSoftDeleted($payment);
        $this->assertModelMissing($proofOfPayment);
    }
}
