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
        abort_unless(auth()->user()->tokenCan('client.index'), Response::HTTP_FORBIDDEN);

        $clients = Client::query()
        ->with(['account', 'avatar', 'pictureId'])
        ->withCount(['properties' => fn($builder) => $builder->where('status', '!=', Property::PROPERTY_CANCELLED)])
        ->paginate(20);

        return ClientResource::collection($clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(auth()->user()->tokenCan('client.create'), Response::HTTP_FORBIDDEN);

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

        return ClientResource::make($resource->load('pictureId', 'avatar', 'account'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {

        abort_unless(auth()->user()->tokenCan('client.show'), Response::HTTP_FORBIDDEN);


        $client->loadCount(['properties' => fn($builder) => $builder->where('status', '!=', Property::PROPERTY_CANCELLED)])
        ->load('avatar', 'account', 'pictureId');

        return ClientResource::make($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Client $client)
    {
        abort_unless(auth()->user()->tokenCan('client.update'), Response::HTTP_FORBIDDEN);

        $data = validator(request()->all(), [
            'first_name' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
            'last_name' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
            'email' => [Rule::when($client->exists, 'sometimes'), 'required', 'email'],
            'phone' => [Rule::when($client->exists, 'sometimes'), 'required'],
            'address' => [Rule::when($client->exists, 'sometimes'),'required', 'string'],
            'city' => [Rule::when($client->exists, 'sometimes'),'required', 'string'],
            'state' => [Rule::when($client->exists, 'sometimes'),'required', 'string'],
            'zipcode' => [Rule::when($client->exists, 'sometimes'), 'required', 'string'],
        ])->validate();

        $client->fill($data);

        DB::transaction(function () use ($client, $data) {
            $client->save();
        });

        return ClientResource::make($client->load('pictureId', 'avatar', 'account'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        abort_unless(auth()->user()->tokenCan('client.destroy'), Response::HTTP_FORBIDDEN);

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

        $client->delete();
    }
}
