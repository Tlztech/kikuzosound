<?php

namespace App\Traits;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\RegistersUsers;

trait AuthenticatesAndRegistersUsers
{
    use AuthenticatesUsers, RegistersUsers {
        AuthenticatesUsers::redirectPath insteadof RegistersUsers;
    }
}
