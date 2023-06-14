<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class PriceTo
{
    public function __construct(protected Request $request)
    {
    }

    public function handle($query, Closure $next)
    {
        if (!$this->request->priceTo) {
            return $next($query);
        }

        return $next($query)->where('price', '<=', $this->request->priceTo * 100);
    }
}
