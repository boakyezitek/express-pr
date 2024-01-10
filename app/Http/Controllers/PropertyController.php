<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Validators\PropertyValidator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Array mapping related models to their corresponding relationships.
     *
     * @var array
     */
    private $relatedModels = [
        'property_amenity' => 'propertyAmenity',
        'property_utility_included' => 'propertyUtilityIncluded',
        'property_type' => 'propertyType',
        'property_parking_type' => 'propertyParkingType',
    ];

    /**
     * Display a paginated listing of the properties with optional filters.
     *
     * @return PropertyResourceCollection
     */
    public function index()
    {

        $properties = Property::query()
            ->with([
                'createdBy',
                'client',
                'propertyAmenity',
                'propertyUtilityIncluded',
                'propertyType',
                'propertyParkingType',
                'images'
            ])
            ->when(request('month_year'), function (Builder $builder) {
                $now = Carbon::now();
                $selectedDate = Carbon::parse(request('month_year'));

                $builder->where(function ($query) use ($now, $selectedDate) {
                    if ($now->isSameMonth($selectedDate) && $now->isSameYear($selectedDate)) {
                        $query->where('available_property_date', '<=', $selectedDate)
                            ->where('status', '!=', Property::PROPERTY_CANCELLED);
                    } else {
                        $query->whereMonth('available_property_date', '=', $selectedDate->month)
                            ->whereYear('available_property_date', '=', $selectedDate->year)
                            ->where('status', '!=', Property::PROPERTY_CANCELLED);
                    }
                });
            })
            ->when(request('available_property_date'), fn ($builder) => $builder->whereDate('available_property_date', request('available_property_date')))
            ->when(request('client'), fn ($builder) => $builder->where('client_id', (int) request('client')))
            ->when(request('status'), fn ($builder) => $builder->where('status', request('status')))
            ->when(request('search'), fn ($builder) => $builder->where('property_name', 'LIKE', '%' . request('search') . '%'))
            ->when(request('property_type'), fn ($builder) => $builder->whereRelation('propertyType', 'id', '=', request('property_type')))

            ->latest()
            ->paginate(20);

        return PropertyResource::collection($properties);
    }

    /**
     * Show the form for creating a new property.
     *
     * @return PropertyResource
     */
    public function create()
    {
        abort_unless(auth()->user()->tokenCan('property.create'), Response::HTTP_FORBIDDEN);

        $attributes = (new PropertyValidator())->validate(
            new Property(),
            request()->all()
        );

        $property = DB::transaction(function () use ($attributes) {
            $property = Property::create(Arr::except($attributes, ['property_amenity', 'property_utility_included', 'property_type', 'property_parking_type']));

            $this->attachRelatedModels($property, $attributes);

            return $property;
        });

        return PropertyResource::make($property->load(
            'createdBy',
            'client',
            'propertyAmenity',
            'propertyUtilityIncluded',
            'propertyType',
            'propertyParkingType',
            'images'
        ));
    }


    /**
     * Display the specified property.
     *
     * @param  Property $property
     * @return PropertyResource
     */
    public function show(Property $property)
    {
        abort_unless(auth()->user()->tokenCan('property.show'), Response::HTTP_FORBIDDEN);

        $resource = $property->load([
            'createdBy',
            'client',
            'propertyAmenity',
            'propertyUtilityIncluded',
            'propertyType',
            'propertyParkingType',
            'images'
        ]);

        return PropertyResource::make($resource);
    }


    /**
     * Update the specified property in storage.
     *
     * @param  Property $property
     * @return PropertyResource
     */
    public function update(Property $property)
    {
        abort_unless(auth()->user()->tokenCan('property.update'), Response::HTTP_FORBIDDEN);

        $attributes = (new PropertyValidator())->validate(
            $property,
            request()->all()
        );


        $property->fill(Arr::except($attributes, ['property_amenity', 'property_utility_included', 'property_type', 'property_parking_type']));

        DB::transaction(function () use ($property, $attributes) {
            if (isset($attributes['property_type'])) {
                $property->propertyType()->detach($attributes['property_type']);
            }
            $property->save();
            $this->syncRelatedModels($property, $attributes);
        });

        $resource = $property->load([
            'createdBy',
            'client',
            'propertyAmenity',
            'propertyUtilityIncluded',
            'propertyType',
            'propertyParkingType',
            'images'
        ]);

        return PropertyResource::make($resource);
    }

    /**
     * Remove the specified property from storage.
     *
     * @param  Property $property
     * @return void
     */
    public function destroy(Property $property)
    {
        abort_unless(auth()->user()->tokenCan('property.destroy'), Response::HTTP_FORBIDDEN);

        $this->removeRelatedModels($property);

        $property->images()->each(function ($image) {
            Storage::delete($image->path);

            $image->delete();
        });

        $property->delete();
    }

    /**
     * Attach related models to the given property.
     *
     * @param  Property $property
     * @param  array    $attributes
     * @return void
     */
    private function attachRelatedModels(Property $property, array $attributes)
    {

        foreach ($this->relatedModels as $attributeKey => $relationshipName) {
            if (isset($attributes[$attributeKey])) {
                $property->{$relationshipName}()->attach($attributes[$attributeKey]);
            }
        }
    }

    /**
     * Sync related models for the given property.
     *
     * @param  Property $property
     * @param  array    $attributes
     * @return void
     */
    private function syncRelatedModels(Property $property, array $attributes)
    {

        foreach ($this->relatedModels as $attributeKey => $relationshipName) {
            if (isset($attributes[$attributeKey])) {
                $property->{$relationshipName}()->sync($attributes[$attributeKey]);
            }
        }
    }

    /**
     * Detach related models from the given property.
     *
     * @param  Property $property
     * @return void
     */
    private function removeRelatedModels($property)
    {
        foreach ($this->relatedModels as $attributeKey => $relationshipName) {
            if ($property->{$relationshipName}) {

                $relatedModels = $property->{$relationshipName};

                $relatedModelIds = $relatedModels->pluck('id')->all();
                $property->{$relationshipName}()->detach($relatedModelIds);
            }
        }
    }
}
