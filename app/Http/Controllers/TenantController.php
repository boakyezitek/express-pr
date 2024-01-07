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
    public function index():AnonymousResourceCollection
    {
        abort_unless(auth()->user()->tokenCan('tenant.index'), Response::HTTP_FORBIDDEN);

        $tenants = Tenant::query()
        ->with(['account', 'avatar', 'pictureId'])
        ->paginate(20);

        return TenantResource::collection($tenants);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():JsonResource
    {
        abort_unless(auth()->user()->tokenCan('tenant.create'), Response::HTTP_FORBIDDEN);

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

        return TenantResource::make($resource->load('pictureId', 'avatar', 'account'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant):JsonResource
    {
        abort_unless(auth()->user()->tokenCan('tenant.show'), Response::HTTP_FORBIDDEN);

        $tenant->load('avatar', 'account', 'pictureId');

        return TenantResource::make($tenant);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Tenant $tenant):JsonResource
    {
        abort_unless(auth()->user()->tokenCan('tenant.update'), Response::HTTP_FORBIDDEN);

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

        $tenant->fill($data);

        DB::transaction(function () use ($tenant) {
            $tenant->save();
        });

        return TenantResource::make($tenant->load('pictureId', 'avatar', 'account'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant):void
    {
        abort_unless(auth()->user()->tokenCan('tenant.destroy'), Response::HTTP_FORBIDDEN);

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

        $tenant->delete();
    }
}
