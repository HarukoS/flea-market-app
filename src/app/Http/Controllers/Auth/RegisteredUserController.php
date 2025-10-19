<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Routing\Controller;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request, CreateNewUser $creator)
    {
        $user = $creator->create($request->all());

        event(new Registered($user)); // メール送信

        // 自動ログインはしない
        return redirect()->route('verify.info')->with('email', $user->email);
    }
}
