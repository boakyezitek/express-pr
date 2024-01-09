<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class VendorController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index(): AnonymousResourceCollection
{
    // Check if the user has the required token for vendor index
    abort_unless(auth()->user()->tokenCan('vendor.index'), Response::HTTP_FORBIDDEN);

    // Fetch paginated vendors with related data
    $vendors = Vendor::query()
        ->with(['account', 'avatar', 'pictureId'])
        ->paginate(20);

    // Return a collection of vendors as a JSON resource
    return VendorResource::collection($vendors);
}

/**
 * Show the form for creating a new resource.
 */
public function create()
{
        // Check if the user has the required token for vendor create
        abort_unless(auth()->user()->tokenCan('vendor.create'), Response::HTTP_FORBIDDEN);

        // Validate incoming data
        $data = validator(request()->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zipcode' => ['required', 'string'],
        ])->validate();

        // Create a new Vendor and related User within a database transaction
        $vendor = new Vendor();
        $resource = DB::transaction(function () use ($vendor, $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make(Str::random(8)),
            ]);

            $data['user_id'] = $user->id;

            $vendor->fill($data);
            $vendor->save();

            return $vendor;
        });

        // Return the created Vendor as a JSON resource
        return VendorResource::make($resource->load('pictureId', 'avatar', 'account'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        // Check if the user has the required token for vendor show
        abort_unless(auth()->user()->tokenCan('vendor.show'), Response::HTTP_FORBIDDEN);

        // Load related data and return the specified Vendor as a JSON resource
        $vendor->load('avatar', 'account', 'pictureId');
        return VendorResource::make($vendor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Vendor $vendor)
    {
        // Check if the user has the required token for vendor update
        abort_unless(auth()->user()->tokenCan('vendor.update'), Response::HTTP_FORBIDDEN);

        // Validate incoming data
        $data = validator(request()->all(), [
            'first_name' => [Rule::when($vendor->exists, 'sometimes'), 'required', 'string'],
            'last_name' => [Rule::when($vendor->exists, 'sometimes'), 'required', 'string'],
            'email' => [Rule::when($vendor->exists, 'sometimes'), 'required', 'email'],
            'phone' => [Rule::when($vendor->exists, 'sometimes'), 'required'],
            'address' => [Rule::when($vendor->exists, 'sometimes'), 'required', 'string'],
            'city' => [Rule::when($vendor->exists, 'sometimes'), 'required', 'string'],
            'state' => [Rule::when($vendor->exists, 'sometimes'), 'required', 'string'],
            'zipcode' => [Rule::when($vendor->exists, 'sometimes'), 'required', 'string'],
        ])->validate();

        // Update the Vendor with the validated data within a database transaction
        $vendor->fill($data);
        DB::transaction(function () use ($vendor) {
            $vendor->save();
        });

        // Return the updated Vendor as a JSON resource
        return VendorResource::make($vendor->load('pictureId', 'avatar', 'account'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        // Check if the user has the required token for vendor destroy
        abort_unless(auth()->user()->tokenCan('vendor.destroy'), Response::HTTP_FORBIDDEN);

        // Delete associated avatar and pictureId records, if they exist
        if ($vendor->avatar) {
            $avatar = $vendor->avatar;
            Storage::delete($avatar->path);
            $avatar->delete();
        }

        if ($vendor->pictureId) {
            $pictureId = $vendor->pictureId;
            Storage::delete($pictureId->path);
            $pictureId->delete();
        }

        // Delete the specified Vendor
        $vendor->delete();
    }
}
