<?php

namespace Wareon\LaravelAdminEmail2Factor\Http\Middleware;

use Closure;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Wareon\LaravelAdminEmail2Factor\Helpers\TwoFactorValidationHelper;

class AuthAdminEmailTwoFactor
{

    public function handle(Request $request, Closure $next)
    {
        /** @var Administrator $admin */
        $admin = $request->user('admin');

        // Nothing to do, we are not logged in
        // or, we are on the 2fa stage so need to skip, or we get stuck in a loop
        if ($admin === null) {
            return $next($request);
        }

        if(!TwoFactorValidationHelper::twoFactorCompleted($admin))
        {
            abort(redirect(route(admin_get_route('auth.2fa.email'))));
        }

        return $next($request);
    }

}
