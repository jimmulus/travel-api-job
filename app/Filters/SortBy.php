<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class SortBy
{
    public function __construct(protected Request $request)
    {
    }

    public function handle($query, Closure $next)
    {
        if (! $this->request->sortBy) {
            return $next($query);
        }

        return $next($query)->orderBy($this->request->sortBy, $this->request->sortOrder ?? 'asc');
    }
}
