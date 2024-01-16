<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\AppValidator\PaymentValidator;
use App\Models\Staff;
use App\Models\Tenant;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(auth()->user()->tokenCan('payment.index'), Response::HTTP_FORBIDDEN);

        $payments = Payment::query()
        ->with(['depositable', 'confirmedBy', 'tenant', 'createdBy', 'property', 'formOfPayment', 'proofOfPayment'])
        ->when(request('payment_date'), fn ($builder) => $builder->whereDate('payment_date', request('payment_date')))
        ->when(request('payment_received'), fn ($builder) => $builder->whereDate('payment_received', request('payment_received')))
        ->when(request('tenant_id'), fn ($builder) => $builder->whereRelation('tenant', 'id', '=', request('tenant_id')))
        ->when(request('property_id'), fn ($builder) => $builder->whereRelation('property', 'id', '=', request('property_id')))
        ->when(request('confirmed_by'), fn ($builder) => $builder->whereRelation('confirmedBy', 'id', '=', request('confirmed_by')))
        ->latest()
        ->paginate(20);

        return PaymentResource::collection($payments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(auth()->user()->tokenCan('payment.create'), Response::HTTP_FORBIDDEN);

        $attributes = (new PaymentValidator())->validate(
            new Payment(),
            request()->all()
        );

        $resource = DB::transaction(function () use ($attributes) {
            if($attributes['deposite_by_type'] === Payment::DEPOSITED_BY_STAFF){
                try {
                    $depositedByUser = Staff::findOrFail($attributes['deposite_by']);
                } catch (ModelNotFoundException $th) {
                    //throw $th;
                    throw ValidationException::withMessages([
                        'deposited_by' => 'Invalide staff id as deposited_by'
                    ]);

                }

            }

            if($attributes['deposite_by_type'] === Payment::DEPOSITED_BY_TENANT){

                try {
                    $depositedByUser = Tenant::find($attributes['deposite_by']);
                } catch (ModelNotFoundException $th) {
                    //throw $th;
                    throw ValidationException::withMessages([
                        'deposited_by' => 'Invalide tenant id as deposited_by'
                    ]);
                }
            }

            return $depositedByUser->payment()->create(Arr::except($attributes, ['deposite_by', 'deposite_by_type']));

        });

        return PaymentResource::make($resource->load(
            'confirmedBy', 'tenant', 'createdBy', 'property', 'formOfPayment', 'proofOfPayment', 'depositable'
        ));
    }


    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        abort_unless(auth()->user()->tokenCan('payment.show'), Response::HTTP_FORBIDDEN);

        return PaymentResource::make($payment->load(
            'confirmedBy', 'tenant', 'createdBy', 'property', 'formOfPayment', 'proofOfPayment', 'depositable'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Payment $payment)
    {
        abort_unless(auth()->user()->tokenCan('payment.update'), Response::HTTP_FORBIDDEN);

        $attributes = (new PaymentValidator())->validate(
            $payment,
            request()->all()
        );

        if(isset($attributes['deposite_by_type'])){

            if($attributes['deposite_by_type'] === Payment::DEPOSITED_BY_STAFF){
                try {
                    $depositedByUser = Staff::findOrFail($attributes['deposite_by']);
                } catch (ModelNotFoundException $th) {
                    //throw $th;
                    throw ValidationException::withMessages([
                        'deposited_by' => 'Invalide staff id as deposited_by'
                    ]);

                }

            }

            if($attributes['deposite_by_type'] === Payment::DEPOSITED_BY_TENANT){

                try {
                    $depositedByUser = Tenant::find($attributes['deposite_by']);
                } catch (ModelNotFoundException $th) {
                    //throw $th;
                    throw ValidationException::withMessages([
                        'deposited_by' => 'Invalide tenant id as deposited_by'
                    ]);
                }
            }

            $attributes['depositable_type'] = get_class($depositedByUser);
            $attributes['depositable_id'] = $depositedByUser->getKey();
        }

        DB::transaction(function() use ($attributes, $payment, $depositedByUser){
            $payment->fill(Arr::except($attributes, ['deposite_by', 'deposite_by_type']));
            $payment->save();
        });

        return PaymentResource::make($payment->load(
            'confirmedBy', 'tenant', 'createdBy', 'property', 'formOfPayment', 'proofOfPayment', 'depositable'
        ));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        abort_unless(auth()->user()->tokenCan('payment.destroy'), Response::HTTP_FORBIDDEN);

         // Delete associated proof of payment images if any
         if ($payment->proofOfPayment()) {
            $payment->proofOfPayment()->each(function ($image) {
                Storage::delete($image->path);
                $image->delete();
            });
        }

        // Delete the specified expense
        return $payment->delete();

    }
}
