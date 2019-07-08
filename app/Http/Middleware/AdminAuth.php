<?php


namespace App\Http\Middleware;

use App\Http\Utils\Utils;
use Closure;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Http\Parser\AuthHeaders;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $admin = session()->get('admin');

        if (isset($admin)) {
            return $next($request);
        }

        return redirect('/login');
    }
}
