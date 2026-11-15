<?php

namespace App\Http\Controllers;

use App\Services\NuwusApi;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('api_token')) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], ['username' => 'username', 'password' => 'password']);

        try {
            $res = (new NuwusApi(token: ''))->post('auth/login/', $data);
        } catch (ConnectionException) {
            return back()->withInput()->withErrors(['api' => 'Backend Django belum berjalan. Jalankan: python manage.py runserver 8001']);
        }

        if (! $res->successful()) {
            return back()->withInput()->withErrors(NuwusApi::errors($res));
        }

        $request->session()->regenerate();
        session(['api_token' => $res->json('token'), 'api_user' => $res->json('user')]);

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang, '.$res->json('user.name').'!');
    }

    public function logout(Request $request)
    {
        if (session()->has('api_token')) {
            try {
                (new NuwusApi)->post('auth/logout/');
            } catch (ConnectionException) {
                // abaikan, tetap hapus session lokal
            }
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
