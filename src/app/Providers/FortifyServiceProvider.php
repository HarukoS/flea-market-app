<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });
    }
}
