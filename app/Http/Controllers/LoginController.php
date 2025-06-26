<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LoginController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        return redirect()->route('products.index')->with('success', 'Login successful!');
    }

    return back()->withErrors([
        'login' => 'Wrong email or password.',
    ])->withInput();
}

    public function showLoginForm()
{
    return view('formLogin'); // Sesuaikan dengan nama file blade kamu
}

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}

}
