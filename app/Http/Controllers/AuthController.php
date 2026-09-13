<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Barua pepe au nenosiri sio sahihi.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:30',
            'role' => 'required|in:user,vendor',
            'shop_name' => 'nullable|string|max:150',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'shop_name' => $validated['role'] === 'vendor' ? ($validated['shop_name'] ?? $validated['name']) : null,
        ]);

        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Umetoka kwenye akaunti yako.');
    }

    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isVendor()) {
            return redirect()->route('vendor.dashboard');
        }
        return redirect()->route('home');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => app()->getLocale() == 'sw' ? 'Tafadhali weka nenosiri lako la sasa.' : 'Please enter your current password.',
            'password.required' => app()->getLocale() == 'sw' ? 'Tafadhali weka nenosiri jipya.' : 'Please enter a new password.',
            'password.min' => app()->getLocale() == 'sw' ? 'Nenosiri lazima liwe na herufi zisizopungua 6.' : 'Password must be at least 6 characters.',
            'password.confirmed' => app()->getLocale() == 'sw' ? 'Nenosiri la kurudia halilingani.' : 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => app()->getLocale() == 'sw' ? 'Nenosiri lako la sasa si sahihi.' : 'The current password is incorrect.',
            ])->with('password_modal_error', true);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $msg = app()->getLocale() == 'sw' ? 'Nenosiri lako limebadilishwa kikamilifu!' : 'Your password has been successfully updated!';
        return back()->with('success', $msg);
    }
}
