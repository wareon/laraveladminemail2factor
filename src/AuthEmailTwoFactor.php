<?php

namespace Wareon\LaravelAdminEmail2Factor;

use Encore\Admin\Extension;

class AuthEmailTwoFactor extends Extension
{
    public static $group = 'auth-email-two-factor';
    public $name = 'auth-email-two-factor';
    public $views = __DIR__ . '/../resources/views';
}
