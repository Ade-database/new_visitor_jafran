<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return new HtmlString('
            <img src="/images/logo_jafran.png" alt="PT. Jafran Indonesia" class="mx-auto h-24 w-auto">
        ');
    }
}