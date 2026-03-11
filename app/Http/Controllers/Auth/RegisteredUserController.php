<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        addJavascriptFile('assets/js/custom/authentication/sign-up/general.js');

        return view('pages/auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.regex' => 'Name must contain only letters and spaces',
        ]);

        // Custom validation: Check space count in name (max 4 spaces)
        $name = $request->input('name');
        $spaceCount = substr_count($name, ' ');
        if ($spaceCount > 4) {
            throw ValidationException::withMessages([
                'name' => ['Name can contain maximum 4 spaces'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto-verify email
            'last_login_at' => \Illuminate\Support\Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);

        // Assign USER role
        $user->assignRole('user');

        // Don't send verification email - just log them in
        Auth::login($user);

        // Redirect to home page
        return redirect()->route('home')->with('success', 'Registration successful! You are now logged in.');
    }

    public function changePassword()
    {
        return view('pages.auth.change-password');
    }

    public function updateSettings(Request $request)
    {
        try {
            $user = Auth::user();
            $updated = false;

            // Validate email if provided
            if ($request->filled('email')) {
                $request->validate([
                    'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                ]);

                $user->email = $request->email;
                $updated = true;
            }

            // Validate and update password ONLY if old_password is provided
            if ($request->filled('old_password')) {
                $request->validate([
                    'old_password' => 'required',
                    'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
                ]);

                // Check if the old password matches
                if (!Hash::check($request->old_password, $user->password)) {
                    return redirect()->back()->withErrors([
                        'old_password' => __('The provided old password does not match our records.'),
                    ])->withInput();
                }

                $user->password = Hash::make($request->password);
                $updated = true;
            }

            // Save changes if any updates were made
            if ($updated) {
                $user->save();
                return redirect()->back()->with('success', __('Settings updated successfully!'));
            }

            return redirect()->back()->with('info', __('No changes were made.'));

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('An error occurred while updating settings. Please try again later.'));
        }
    }
}
