<?php

namespace App\Http\Controllers;

use App\Models\ParkingItem;
use App\Http\Requests\StoreParkingItemRequest;
use App\Http\Requests\UpdateParkingItemRequest;

class ParkingItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParkingItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkingItem $parkingItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingItem $parkingItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParkingItemRequest $request, ParkingItem $parkingItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingItem $parkingItem)
    {
        //
    }
}
