<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Property;
use App\Models\Staff;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExpensesTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_application_can_load_all_expenses_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);


        Expense::factory(4)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        $expenses = Expense::factory()->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        $images = $expenses->proofOfPayment()->create(
            ['path' => 'proofofpayment.png'],
        );

        $response = $this->get('/api/expenses');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id']]])
            ->assertJsonCount(5, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }


    public function test_application_can_load_all_expenses_with_date_paid_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);


        Expense::factory(5)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        Expense::factory(3)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
            'date_paid' => '2023-12-10',
        ]);

        $response = $this->get('/api/expenses?date_paid=2023-12-10');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id']]])
            ->assertJsonCount(3, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_all_expenses_property_id_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        $property_2 = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);
        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);


        Expense::factory(5)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        Expense::factory(3)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property_2->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
            'date_paid' => '2023-12-10',

        ]);

        $response = $this->get('/api/expenses?property_id=' . $property_2->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id']]])
            ->assertJsonCount(3, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_all_expenses_with_paid_by_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid by staff
        $paid_by_user_2 = User::factory()->create();
        $paidstaff2 = Staff::factory()->create(['user_id' => $paid_by_user_2->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);


        Expense::factory(5)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        Expense::factory(3)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff2->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
            'date_paid' => '2023-12-10',

        ]);

        $response = $this->get('/api/expenses?paid_by=' . $paidstaff2->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id']]])
            ->assertJsonCount(3, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_load_all_expenses_with_paid_to_in_paginated_manner(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);

        // paid to vendor
        $paid_to_user_2 = User::factory()->create();
        $vendor2 = Vendor::factory()->create(['user_id' => $paid_to_user_2->id]);

        Expense::factory(5)->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        Expense::factory(3)->create([
            'paid_to' => $vendor2->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
            'date_paid' => '2023-12-10',

        ]);

        $response = $this->get('/api/expenses?paid_to=' . $vendor2->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id']]])
            ->assertJsonCount(3, 'data');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));
    }

    public function test_application_can_create_expenses(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);

        $response = $this->postJson('/api/expenses', [
            "date_paid" => "2023-12-10",
            "description" => "Alias laboriosam laborum suscipit sunt voluptatum. Harum at vel tenetur commodi veniam eligendi. Ipsa ipsam eius alias numquam. Natus voluptatem quae expedita earum modi enim.",
            "expense_amount" => 25,
            "expenses_category_id" => 1,
            "is_reimbursement_necessary" => 2,
            "reimbursement_date" => "2024-01-15",
            "paid_to" => $vendor->id,
            "paid_by" => $paidstaff->id,
            "property_id" => $property->id,
            "expenses_category_id" => 1,
            "created_by" => $staff->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.date_paid', '2023-12-10')
            ->assertJsonPath('data.expense_amount', 25)
            ->assertJsonPath('data.is_reimbursement_necessary', 2)
            ->assertJsonPath('data.reimbursement_date', '2024-01-15')
            ->assertJsonPath('data.paid_to.id', $vendor->id)
            ->assertJsonPath('data.property_id', $property->id);
    }

    public function test_application_can_update_expenses(): void
    {
        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);

        $expenses = Expense::factory()->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        $response = $this->putJson('/api/expenses/' . $expenses->id, [
            "date_paid" => "2023-12-10",
            "description" => "Alias laboriosam laborum suscipit sunt voluptatum. Harum at vel tenetur commodi veniam eligendi. Ipsa ipsam eius alias numquam. Natus voluptatem quae expedita earum modi enim.",
            "expense_amount" => 25,
            "expenses_category_id" => 1,
            "is_reimbursement_necessary" => 2,
            "reimbursement_date" => "2024-01-15",
            "expenses_category_id" => 3,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.date_paid', '2023-12-10')
            ->assertJsonPath('data.expense_amount', 25)
            ->assertJsonPath('data.is_reimbursement_necessary', 2)
            ->assertJsonPath('data.reimbursement_date', '2024-01-15');
    }

    public function test_application_can_delete_expenses(): void
    {
        Storage::put('/proofofpayment.png', 'empty');

        $user = User::factory()->create();
        $staff = Staff::factory()->create(['user_id' => $user->id, 'staff_type' => Staff::MANAGER]);

        $this->actingAs($user);


        $cliet_user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $cliet_user->id]);

        $property = Property::factory()->create(['client_id' => $client->id, 'created_by' => $staff->id]);

        // paid by staff
        $paid_by_user = User::factory()->create();
        $paidstaff = Staff::factory()->create(['user_id' => $paid_by_user->id, 'staff_type' => Staff::STAFF]);

        // paid to vendor
        $paid_to_user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $paid_to_user->id]);

        $expenses = Expense::factory()->create([
            'paid_to' => $vendor->id,
            'paid_by' => $paidstaff->id,
            'property_id' => $property->id,
            'expenses_category_id' => 1,
            'created_by' => $staff->id,
        ]);

        $images = $expenses->proofOfPayment()->create(
            ['path' => 'proofofpayment.png'],
        );

        $response = $this->deleteJson('/api/expenses/' . $expenses->id);

        $response->assertOk();

        $this->assertSoftDeleted($expenses);

        $this->assertModelMissing($images);

        Storage::assertMissing('proofofpayment.png');
    }
}
