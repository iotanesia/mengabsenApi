<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\ApiHelper as Helper;
use Carbon\Carbon;
use Jenssegers\Agent\Facades\Agent;
use Stevebauman\Location\Facades\Location;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->bearerToken());
        $usr = Helper::getUserJwt($request);
        $request->current_user = $usr;
        return $next($request);
    }
}
