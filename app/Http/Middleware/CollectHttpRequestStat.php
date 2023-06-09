<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;



class CollectHttpRequestStat
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
        $httpRequest = new \App\Models\HttpRequest();
        $httpRequest->name = env('APP_NAME');
        $httpRequest->method = $request->method();
        $httpRequest->path = $request->path();
        $httpRequest->query = json_encode($request->all());
        $httpRequest->user_id = \auth()->check() ? \auth()->user()->id : null;
        $httpRequest->user_agent = $request->userAgent();
        $httpRequest->ip = $request->ip();
        $httpRequest->save();

        return $next($request);
    }
}
