<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request) {
        try {
            $validatedData = $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
            if (Auth::attempt($validatedData)) {
                return redirect('/dashboard');
            }
            else {
                return redirect()->back()->with('error', 'Username atau Password salah.');
            }
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
    
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
