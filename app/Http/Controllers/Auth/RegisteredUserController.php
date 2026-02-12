<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Fetch all units EXCEPT the admin unit
        $units = Unit::where('id', '!=', Unit::ADMIN_UNIT_ID)->get();
        // OR use the scope:
        // $units = Unit::nonAdmin()->get();
        
        return view('auth.register', compact('units'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'unit_id' => [
                'required', 
                'exists:units,id',
                function ($attribute, $value, $fail) {
                    // Prevent registration with admin unit
                    if ($value == Unit::ADMIN_UNIT_ID) {
                        $fail('You cannot register with this unit.');
                    }
                },
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'unit_id' => $request->unit_id,
            'is_admin' => false, // Always set to false for registration
        ]);

        // Fire the Registered event - this sends the verification email
        event(new Registered($user));

        // DO NOT log the user in - they need to verify email first
        // Auth::login($user); // <-- REMOVED THIS LINE

        // Redirect to login page with message to check email
        return redirect()->route('login')->with('status', 'Registration successful! Please check your email to verify your account before signing in.');
    }
}