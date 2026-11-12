<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request) {
        $cred = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($cred)) {
            $request->session()->regenerate();
            return Auth::user()->role === 'admin' ? redirect()->route('admin.dashboard') : redirect()->route('home');
        }
        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function register(Request $request) {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required|min:6|confirmed']);
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'role' => 'pembeli']);
        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}