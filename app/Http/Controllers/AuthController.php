<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) return $this->redirectToDashboard();
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            auth()->user()->update(['last_login' => now()]);

            return $this->redirectToDashboard();
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        if (auth()->check()) return $this->redirectToDashboard();
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:users',
            'fakultas' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'fakultas' => $validated['fakultas'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Selamat datang di SulaHaring, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function redirectToDashboard()
    {
        return match(auth()->user()->role) {
            'admin' => redirect('/admin/dashboard'),
            'pakar' => redirect('/pakar/dashboard'),
            default => redirect('/dashboard'),
        };
    }
}
