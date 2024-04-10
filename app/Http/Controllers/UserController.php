<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function signup() {
        return view('auth.signup');
    }

    public function signupUser() {
        // Validate form
        $formFields = request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:6|confirmed',
        ]);

        // Set user type
        $formFields['user_type'] = 'member';

        // Hash password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create user
        $user = User::create($formFields);

        // login user
        auth()->login($user);

        // redirect back to homepage, using alpinejs
        return redirect('/')->with('message', 'User created and logged in!');
    }

    public function logout() {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');
    }

    public function loginUser() {
        // validate form
        $formFields = request()->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        // attempt to login
        if (auth()->attempt($formFields)) {
            // regenerate session
            request()->session()->regenerate();

            return redirect('/')->with('message', 'You have been logged in!');
        }

        return back()->with('message', 'Invalid credentials!');
    }

    public function recovery() {
        return view('auth.recovery');
    }

    public function sendRecoveryEmail(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status), 'message' => 'Password reset link sent!'])
            : back()->withErrors(['email' => __($status), 'message' => 'Password reset link not sent!']);
    }

    public function resetPasswordForm(Request $request, string $token) {
        $email = $request->input('email');

        $user = User::where('email', $email)->firstOrFail();

        if(!Password::tokenExists($user, $token)) {
            return redirect('/recovery')->withErrors(['email' => 'Invalid token!', 'message' => 'Invalid token!']);
        }
        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with(['status' => __($status), 'message' => 'Password reset successful!'])
            : back()->withErrors(['email' => __($status), 'message' => 'Password reset not successful!']);
    }
}
