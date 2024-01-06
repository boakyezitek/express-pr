<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Resources\StaffResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{


    /**
     * Display a paginated list of staff members.
     */
    public function index()
    {
        /**
         * Abort the request unless the user has the 'staff.index' scope.
         */
        abort_unless(auth()->user()->tokenCan('staff.index'), Response::HTTP_FORBIDDEN);

        /**
         * Query staff members based on staff_type parameter, if provided.
         * Include 'account' and 'avatar' relationships and paginate the results.
         */

        $staff = Staff::query()
            ->when(
                request('staff_type'),
                fn ($builder) => $builder->where('staff_type', request('staff_type')),
                fn ($builder) => $builder
            )
            ->with(['account', 'avatar'])
            ->paginate(20);

        /**
         * Return a collection of StaffResource.
         */
        return StaffResource::collection($staff);
    }

    /**
     * Display a list of staff members visible on the website.
     */
    public function visibleOnWebsite()
    {
        /**
         * Query staff members that are visible on the website.
         * Include the 'avatar' relationship and return a collection of StaffResource.
         */
        $staff = Staff::query()
            ->where('is_visible_on_website', true)
            ->with('avatar')
            ->get();

        return StaffResource::collection($staff);
    }

    /**
     * Display the form for creating a new staff member.
     */
    public function create()
    {
        /**
         * Abort the request unless the user has the 'staff.create' scope.
         */
        abort_unless(auth()->user()->tokenCan('staff.create'), Response::HTTP_FORBIDDEN);

        /**
         * Validate input data and create a new Staff instance.
         * Use a database transaction to ensure data consistency.
         * Return the created StaffResource.
         */
        $data = validator(request()->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'staff_type' => ['required', 'numeric', 'in:1,2,3'],
        ])->validate();

        $staff = new Staff();

        $staff = DB::transaction(function () use ($staff, $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make(Str::random(8)),
            ]);

            $data['user_id'] = $user->id;
            $staff->fill($data)->save;

            return $staff;
        });

        return StaffResource::make($staff->load('account', 'avatar'));
    }

    /**
     * Display the specified staff member.
     */
    public function show(Staff $staff)
    {
        /**
         * Abort the request unless the user has the 'staff.show' scope.
         */
        abort_unless(auth()->user()->tokenCan('staff.show'), Response::HTTP_FORBIDDEN);

        /**
         * Return the specified staff member as a StaffResource.
         */
        return StaffResource::make($staff->load('account', 'avatar'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Staff $staff)
    {
        /**
         * Abort the request unless the user has the 'staff.update' scope.
         */
        abort_unless(auth()->user()->tokenCan('staff.update'), Response::HTTP_FORBIDDEN);

        /**
         * Validate input data and update the staff member.
         * Use a database transaction to ensure data consistency.
         * Return the updated StaffResource.
         */
        $data = validator(request()->all(), [
            'first_name' => [Rule::when($staff->exists, 'sometimes'), 'required', 'string'],
            'last_name' => [Rule::when($staff->exists, 'sometimes'), 'required', 'string'],
            'email' => [Rule::when($staff->exists, 'sometimes'), 'required', 'email'],
            'phone' => [Rule::when($staff->exists, 'sometimes'), 'required'],
            'staff_type' => [Rule::when($staff->exists, 'sometimes'), 'required', 'numeric', 'in:1,2,3'],
        ])->validate();

        $staff->fill($data);



        DB::transaction(function () use ($staff, $data) {
            $staff->save();
        });

        return StaffResource::make($staff->load('account', 'avatar'));
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Staff $staff)
    {
        /**
         * Abort the request unless the user has the 'staff.destroy' scope.
         */
        abort_unless(auth()->user()->tokenCan('staff.destroy'), Response::HTTP_FORBIDDEN);

        /**
         * Delete the staff member's avatar if it exists, then delete the staff member.
         */
        if ($staff->avatar) {
            $avatar = $staff->avatar;
            Storage::delete($avatar->path);

            $avatar->delete();
        }

        $staff->delete();
    }
}
