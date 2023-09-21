<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /************************************************************************
    *Registration Form
    *************************************************************************/
    public function registerForm()
    {        
        return view('auth.register');
    }

    /************************************************************************
    *User registration
    *************************************************************************/
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-])/',
            'terms_and_condition' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => Str::random(32),
        ]);

        //Email - Account Verification
        if($user) //Success
        {
            $email = $user->email;
            //$email = 'karthiknykb@gmail.com';

            //$url = $request->getSchemeAndHttpHost() .'/'. $user->verification_token;

            $url = route('verify.email', $user->verification_token);

            try
            {
                $data = array('name' => $user->name, 'url' => $url);

                \Mail::send('emails.account_verification', $data, function ($message) use($email)
                {
                    $message->to($email)->subject('GC - Account Verification');
                });

                \Log::debug('Email sent successfully : '. $email);
            }
            catch (\Exception $e) 
            {
                \Log::error("Error while sending email : ". $e->getMessage());
            }

            return redirect()->route('login')->with('success', 'Registered successfully. Please check your email and verify.');
        }
        else //Fail
        {
            return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
        }
    }

    /************************************************************************
    *Login Form
    *************************************************************************/
    public function loginForm()
    {
        //If user is already logged in then redirect to home
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    /************************************************************************
    *Login function
    *************************************************************************/
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //login credentials
        $credentials = $request->only('email', 'password');

        //authentication logic
        if (Auth::attempt($credentials))
        {
            $user = Auth::user();
            
            // Check if the email is verified
            if ($user->hasVerifiedEmail()) 
            {
                return redirect()->intended('/2fa');
            }
            else
            {
                Auth::logout(); // Log out the user if email is not verified
                return redirect()->back()->withInput()->withErrors(['login' => 'Your email address is not verified. Please check your email for a verification link.']);
            }
        }
        else //Fail
        {
            return redirect()->back()->withInput()->withErrors(['login' => 'Invalid credentials.']);
        }
    }

    /************************************************************************
    *Logout function
    *************************************************************************/
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /************************************************************************
    *Verify Account
    *************************************************************************/
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return '
                <h1>Invalid Token..!</h1>
                <a href="/register">
                    <button type="button">Signup</button>
                </a>
                ';
        }

        $user->email_verified_at = now();
        $user->verification_token = null; //clear token
        $user->save();

        return '
                <h1>Account Verified..!</h1>
                <a href="/login">
                    <button type="button">Login</button>
                </a>
                ';
    }
}
