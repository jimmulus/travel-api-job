<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourRequest;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TourController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TourResource::collection(Tour::all());
    }

    public function store(Travel $travel, TourRequest $request): TourResource
    {
        return TourResource::make($travel->tours()->create($request->validated()));
    }
}
