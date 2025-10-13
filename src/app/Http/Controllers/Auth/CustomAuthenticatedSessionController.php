<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

class CustomAuthenticatedSessionController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'ログイン情報が登録されていません。']);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('verify.info')->with('email', $user->email);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if (empty($user->image) || empty($user->postal_code) || empty($user->address)) {
            return redirect()->route('profile')->with('message', 'プロフィールを登録してください。');
        }

        return redirect()->intended('/');
    }
}
