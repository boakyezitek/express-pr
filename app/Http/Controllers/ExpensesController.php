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
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ensure user has the required token for client index, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('expenses.index'), Response::HTTP_FORBIDDEN);

        $expenese = Expense::query()
        ->with(['createdBy', 'paidBy', 'paidTo', 'proofOfPayment', 'expensesCategory', 'property'])
        ->when(request('date_paid'), fn($builder) => $builder->whereDate('date_paid', request('date_paid')))
        ->when(request('property_id'), fn($builder) => $builder->whereRelation('property', 'id', '=', request('property_id')))
        ->when(request('paid_by'), fn($builder) => $builder->whereRelation('paidBy', 'id', '=', request('paid_by')))
        ->when(request('paid_to'), fn($builder) => $builder->whereRelation('paidTo', 'id', '=', request('paid_to')))
        ->paginate(20);

        return ExpensesResource::collection($expenese);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(auth()->user()->tokenCan('expenses.create'), Response::HTTP_FORBIDDEN);

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
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        abort_unless(auth()->user()->tokenCan('expenses.show'), Response::HTTP_FORBIDDEN);

        return ExpensesResource::make($expense->load('createdBy', 'paidBy', 'paidTo', 'proofOfPayment', 'expensesCategory', 'property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Expense $expense)
    {
        abort_unless(auth()->user()->tokenCan('expenses.update'), Response::HTTP_FORBIDDEN);

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
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        abort_unless(auth()->user()->tokenCan('expenses.destroy'), Response::HTTP_FORBIDDEN);

        if ($expense->proofOfPayment()) {
            $expense->proofOfPayment()->each(function ($image) {
                Storage::delete($image->path);

                $image->delete();
            });
        }

        $expense->delete();
    }
}
