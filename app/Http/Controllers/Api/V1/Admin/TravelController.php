<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TravelController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TravelResource::collection(Travel::paginate());
    }

    public function store(TravelRequest $request): TravelResource
    {
        $validated = $request->validated();

        $travel = Travel::create($validated);

        return TravelResource::make($travel);
    }

    public function update(Travel $travel, TravelRequest $request): TravelResource
    {
        $validated = $request->validated();

        $travel->update($validated);

        return TravelResource::make($travel);
    }
}
