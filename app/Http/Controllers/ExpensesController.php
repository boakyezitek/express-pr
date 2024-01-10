<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Http\Requests\StoreExpeneseRequest;
use App\Http\Requests\UpdateExpeneseRequest;
use App\Http\Resources\ExpensesResource;
use App\Models\AppValidator\ExpensesValidator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpensesController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index()
    {
        // Ensure user has the required token for viewing expenses, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('expenses.index'), Response::HTTP_FORBIDDEN);

        // Query expenses with relationships and apply filters if provided in the request
        $expenses = Expense::query()
            ->with(['createdBy', 'paidBy', 'paidTo', 'proofOfPayment', 'expensesCategory', 'property'])
            ->when(request('date_paid'), fn ($builder) => $builder->whereDate('date_paid', request('date_paid')))
            ->when(request('property_id'), fn ($builder) => $builder->whereRelation('property', 'id', '=', request('property_id')))
            ->when(request('paid_by'), fn ($builder) => $builder->whereRelation('paidBy', 'id', '=', request('paid_by')))
            ->when(request('paid_to'), fn ($builder) => $builder->whereRelation('paidTo', 'id', '=', request('paid_to')))
            ->paginate(20);

        return ExpensesResource::collection($expenses);
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        // Ensure user has the required token for creating expenses, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('expenses.create'), Response::HTTP_FORBIDDEN);

        // Validate and create a new expense within a database transaction
        $attributes = (new ExpensesValidator())->validate(
            $expense = new Expense(),
            request()->all()
        );

        $expenses = DB::transaction(function () use ($expense, $attributes) {
            $expense->fill($attributes);
            $expense->save();

            return $expense;
        });

        return ExpensesResource::make($expense->load('createdBy', 'paidBy', 'paidTo', 'proofOfPayment', 'expensesCategory', 'property'));
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense)
    {
        // Ensure user has the required token for viewing a specific expense, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('expenses.show'), Response::HTTP_FORBIDDEN);

        // Return the specified expense with relationships loaded
        return ExpensesResource::make($expense->load('createdBy', 'paidBy', 'paidTo', 'proofOfPayment', 'expensesCategory', 'property'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Expense $expense)
    {
        // Ensure user has the required token for updating expenses, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('expenses.update'), Response::HTTP_FORBIDDEN);

        // Validate and update the specified expense within a database transaction
        $attributes = (new ExpensesValidator())->validate(
            $expense,
            request()->all()
        );

        $expense->fill($attributes);

        DB::transaction(function () use ($expense) {
            $expense->save();
        });

        return ExpensesResource::make($expense->load('createdBy', 'paidBy', 'paidTo', 'proofOfPayment', 'expensesCategory', 'property'));
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense)
    {
        // Ensure user has the required token for deleting expenses, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('expenses.destroy'), Response::HTTP_FORBIDDEN);

        // Delete associated proof of payment images if any
        if ($expense->proofOfPayment()) {
            $expense->proofOfPayment()->each(function ($image) {
                Storage::delete($image->path);
                $image->delete();
            });
        }

        // Delete the specified expense
        return $expense->delete();
    }
}
