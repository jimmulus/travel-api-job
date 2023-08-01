<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TravelTourController extends Controller
{
    public function __invoke(Travel $travel): AnonymousResourceCollection
    {
        return TourResource::collection($travel->tours()->paginate());
    }
}
