<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ensure user has the required token for client index, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('client.index'), Response::HTTP_FORBIDDEN);

        // Retrieve clients with related data and pagination
        $clients = Client::query()
            ->with(['account', 'avatar', 'pictureId'])
            ->withCount(['properties' => fn ($builder) => $builder->where('status', '!=', Property::PROPERTY_CANCELLED)])
            ->paginate(20);

        // Return a collection of clients as a JSON resource
        return ClientResource::collection($clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ensure user has the required token for client creation, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('client.create'), Response::HTTP_FORBIDDEN);

        // Validate input data
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

        // Create a new Client and associated User in a database transaction
        $client = new Client();
        $resource = DB::transaction(function () use ($client, $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make(Str::random(8)),
            ]);

            $data['user_id'] = $user->id;

            $client->fill($data);
            $client->save();

            return $client;
        });

        // Return the created client as a JSON resource
        return ClientResource::make($resource->load('pictureId', 'avatar', 'account'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        // Ensure user has the required token for showing a client, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('client.show'), Response::HTTP_FORBIDDEN);

        // Load counts and related data for the specified client
        $client->loadCount(['properties' => fn ($builder) => $builder->where('status', '!=', Property::PROPERTY_CANCELLED)])
            ->load('avatar', 'account', 'pictureId');

        // Return the specified client as a JSON resource
        return ClientResource::make($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Client $client)
    {
        // Ensure user has the required token for updating a client, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('client.update'), Response::HTTP_FORBIDDEN);

        // Validate input data for updating a client
        $data = validator(request()->all(), [
            'first_name' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
            'last_name' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
            'email' => [Rule::when($client->exists, 'sometimes'), 'required', 'email'],
            'phone' => [Rule::when($client->exists, 'sometimes'), 'required'],
            'address' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
            'city' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
            'state' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
            'zipcode' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
        ])->validate();

        // Fill the client model with the updated data and save it in a database transaction
        $client->fill($data);
        DB::transaction(function () use ($client, $data) {
            $client->save();
        });

        // Return the updated client as a JSON resource
        return ClientResource::make($client->load('pictureId', 'avatar', 'account'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // Ensure user has the required token for deleting a client, otherwise, return forbidden status
        abort_unless(auth()->user()->tokenCan('client.destroy'), Response::HTTP_FORBIDDEN);

        // Delete associated avatar and pictureId if they exist
        if ($client->avatar) {
            $avatar = $client->avatar;
            Storage::delete($avatar->path);
            $avatar->delete();
        }

        if ($client->pictureId) {
            $pictureId = $client->pictureId;
            Storage::delete($pictureId->path);
            $pictureId->delete();
        }

        // Delete the client
        $client->delete();
    }
}
