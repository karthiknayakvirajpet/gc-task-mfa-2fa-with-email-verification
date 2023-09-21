<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//LoginController routes
Route::controller(App\Http\Controllers\Auth\LoginController::class)->group(function () 
{
    //registration form
    Route::get('/register', 'registerForm')->name('register.form');

    //register
    Route::post('/register', 'register')->name('register');

    //login form
    Route::get('/login', 'loginForm')->name('login.form');
    Route::get('/', 'loginForm');

    //login
    Route::post('/login', 'login')->name('login');

    //logout
    Route::any('/logout', 'logout')->name('logout');

    //Verify email
    Route::get('/verify-email/{token}', 'verify')->name('verify.email');

});


Route::middleware(['auth'])->group(function () 
{
    //Dashboard
    Route::get('/dashboard', function () {
        return '
            <h1>Hello,</h1>
            <h1>'.Auth::user()->name.'...!!!</h1><br><br>
            <a href="/2fa">
                <button type="button">Two Factor Authentication</button>
            </a>
            
            <a href="/logout">
                <button type="button">Logout</button>
            </a>
            ';
    });

    //2FA - Google App - LoginSecurityController
    //https://shouts.dev/articles/laravel-two-factor-authentication-with-google-authenticator
    //https://github.com/antonioribeiro/google2fa-qrcode/issues/22
    Route::controller(App\Http\Controllers\LoginSecurityController::class)->group(function () 
    {
        Route::group(['prefix'=>'2fa'], function(){
            Route::get('/','show2faForm');
            Route::post('/generateSecret','generate2faSecret')->name('generate2faSecret');
            Route::post('/enable2fa','enable2fa')->name('enable2fa');
            Route::post('/disable2fa','disable2fa')->name('disable2fa');
        });
    });

});



// 2fa middleware
Route::post('/2faVerify', function () {
    return redirect(URL()->previous());
})->name('2faVerify')->middleware('2fa');



