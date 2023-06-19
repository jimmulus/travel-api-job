<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToursListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pipeline\Pipeline;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Travel $travel, ToursListRequest $request): AnonymousResourceCollection
    {
        if (! $travel->is_public) {
            abort(404);
        }

        if ($request) {
            $tours = app(Pipeline::class)
                ->send($travel->tours()->getQuery())
                ->through([
                    \App\Filters\PriceFrom::class,
                    \App\Filters\PriceTo::class,
                    \App\Filters\DateFrom::class,
                    \App\Filters\DateTo::class,
                    \App\Filters\SortBy::class,
                ])
                ->thenReturn()
                ->orderBy('starting_date')
                ->paginate();

            return TourResource::collection($tours);
        }
    }
}
