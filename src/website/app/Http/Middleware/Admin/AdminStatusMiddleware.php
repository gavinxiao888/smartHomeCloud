<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Session;

class AdminStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        if ((Session::has('uid')) && (Session::has('role')) && (Session::has('nickname'))) {
            return $next($request);
        } else if ((!empty($_REQUEST['username'])) && (!empty($_REQUEST['password']))) {
            return $next($request);
        } else {
            return response()->view('admin.login');
        }
    }
}
