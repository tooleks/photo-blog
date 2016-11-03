<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class SearchParametersSpreader
 * @package App\Http\Middleware
 */
class SearchParametersSpreader
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $query = trim((string)$request->query->get('query'));
        $tag = trim((string)$request->query->get('tag'));

        $request->query->set('query', $query);
        $request->query->set('tag', $tag);

        return $next($request);
    }
}
