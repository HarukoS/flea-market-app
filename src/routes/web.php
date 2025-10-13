<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{id}', [ItemController::class, 'detail'])->name('items.show');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.detail');
Route::get('/items', [ItemController::class, 'index'])->name('items.index');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/verify-info', function () {
    return view('auth.verify-email');
})->name('verify.info');

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//認証メールの再送
Route::post('/verification-notification-guest', function (Request $request) {
    $request->validate(['email' => 'required|email|exists:users,email']);

    $user = User::where('email', $request->email)->first();

    // 認証済みの場合は何もしない
    if ($user->hasVerifiedEmail()) {
        return back()->with('status', '既に認証済みです。');
    }

    // 認証メール送信
    event(new Registered($user));

    return back()->with('status', '認証メールを再送しました。');
})->name('verification.send.guest');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! URL::hasValidSignature($request)) {
        abort(403);
    }

    Auth::login($user);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    $user = Auth::user();
    $tab = 'recommend';

    return redirect()->route('profile')->with('tab', 'recommend', 'user');
})->name('verification.verify');

Route::get('/mypage/profile', function () {
    return view('profile');
})->name('profile')->middleware('auth');

Route::post('/profile/update', [UsersController::class, 'profileUpdate'])->name('profile.update');

// ログインフォーム表示
Route::get('/login', [CustomAuthenticatedSessionController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])
    ->name('login')->middleware('guest');

Route::middleware('auth', 'verified')->group(function () {
    Route::post('/like', [ItemController::class, 'toggleLike']);
});

Route::middleware('auth', 'verified')->group(function () {
    Route::post('/comment', [ItemController::class, 'comment']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/purchase/{item}', [ItemController::class, 'purchasePage'])->name('purchase.page');
});

Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');

Route::post('/purchase/{item}/address/update', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

Route::post('/purchase', [PurchaseController::class, 'purchaseCreate'])->name('purchase');

Route::get('/mypage', [UsersController::class, 'mypage'])->name('mypage')->middleware(['auth', 'verified']);

Route::get('/sell', [UsersController::class, 'sellpage'])->middleware(['auth', 'verified']);

Route::post('/sellitem', [UsersController::class, 'sellitem'])->middleware(['auth', 'verified']);
