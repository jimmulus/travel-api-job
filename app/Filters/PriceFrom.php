<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class PriceFrom
{
    public function __construct(protected Request $request)
    {
    }

    public function handle($query, Closure $next)
    {

        if (!$this->request->priceFrom) {
            return $next($query);
        }

        return $next($query)->where('price', '>=', $this->request->priceFrom * 100);
    }
}
