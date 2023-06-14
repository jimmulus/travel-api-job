<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class DateTo
{
    public function __construct(protected Request $request)
    {
    }

    public function handle($query, Closure $next)
    {
        if (!$this->request->dateTo) {
            return $next($query);
        }

        return $next($query)->where('ending_date', '<=', $this->request->dateTo);
    }
}
