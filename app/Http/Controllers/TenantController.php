<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Http\Resources\TenantResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        // Check if the user has the required token for tenant index
        abort_unless(auth()->user()->tokenCan('tenant.index'), Response::HTTP_FORBIDDEN);

        // Fetch paginated tenants with related data
        $tenants = Tenant::query()
            ->with(['account', 'avatar', 'pictureId'])
            ->paginate(20);

        // Return a collection of tenants as a JSON resource
        return TenantResource::collection($tenants);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResource
    {
        // Check if the user has the required token for tenant create
        abort_unless(auth()->user()->tokenCan('tenant.create'), Response::HTTP_FORBIDDEN);

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

        // Create a new Tenant and related User within a database transaction
        $tenant = new Tenant();
        $resource = DB::transaction(function () use ($tenant, $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make(Str::random(8)),
            ]);

            $data['user_id'] = $user->id;

            $tenant->fill($data);
            $tenant->save();

            return $tenant;
        });

        // Return the created Tenant as a JSON resource
        return TenantResource::make($resource->load('pictureId', 'avatar', 'account'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant): JsonResource
    {
        // Check if the user has the required token for tenant show
        abort_unless(auth()->user()->tokenCan('tenant.show'), Response::HTTP_FORBIDDEN);

        // Load related data and return the specified Tenant as a JSON resource
        $tenant->load('avatar', 'account', 'pictureId');
        return TenantResource::make($tenant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Tenant $tenant): JsonResource
    {
        // Check if the user has the required token for tenant update
        abort_unless(auth()->user()->tokenCan('tenant.update'), Response::HTTP_FORBIDDEN);

        // Validate incoming data
        $data = validator(request()->all(), [
            'first_name' => [Rule::when($tenant->exists, 'sometimes'), 'required', 'string'],
            'last_name' => [Rule::when($tenant->exists, 'sometimes'), 'required', 'string'],
            'email' => [Rule::when($tenant->exists, 'sometimes'), 'required', 'email'],
            'phone' => [Rule::when($tenant->exists, 'sometimes'), 'required'],
            'address' => [Rule::when($tenant->exists, 'sometimes'), 'required', 'string'],
            'city' => [Rule::when($tenant->exists, 'sometimes'), 'required', 'string'],
            'state' => [Rule::when($tenant->exists, 'sometimes'), 'required', 'string'],
            'zipcode' => [Rule::when($tenant->exists, 'sometimes'), 'required', 'string'],
        ])->validate();

        // Update the Tenant with the validated data within a database transaction
        $tenant->fill($data);
        DB::transaction(function () use ($tenant) {
            $tenant->save();
        });

        // Return the updated Tenant as a JSON resource
        return TenantResource::make($tenant->load('pictureId', 'avatar', 'account'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant): void
    {
        // Check if the user has the required token for tenant destroy
        abort_unless(auth()->user()->tokenCan('tenant.destroy'), Response::HTTP_FORBIDDEN);

        // Delete associated avatar and pictureId records, if they exist
        if ($tenant->avatar) {
            $avatar = $tenant->avatar;
            Storage::delete($avatar->path);
            $avatar->delete();
        }

        if ($tenant->pictureId) {
            $pictureId = $tenant->pictureId;
            Storage::delete($pictureId->path);
            $pictureId->delete();
        }

        // Delete the specified Tenant
        $tenant->delete();
    }
}
