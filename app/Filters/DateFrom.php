<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class DateFrom
{
    public function __construct(protected Request $request)
    {
    }

    public function handle($query, Closure $next)
    {
        if (! $this->request->dateFrom) {
            return $next($query);
        }

        return $next($query)->where('starting_date', '>=', $this->request->dateFrom);
    }
}
