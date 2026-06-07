<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\user;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $login = $request->validated()['username'];

        $account = user::query()
            ->where(function ($query) use ($login) {
                $query->where('email', $login)
                    ->orWhere('nama', $login);
            })
            ->first();

        if (!$account || !Hash::check($request->validated()['password'], $account->password)) {
            AuditLogger::logAuthAttempt($login, false);
            return back()->withErrors([
                'username' => 'Username/email atau password tidak valid.',
            ])->withInput();
        }

        // Check if user role is valid (admin or user)
        if (!in_array($account->role, ['admin', 'user'])) {
            AuditLogger::logAuthAttempt($login, false);
            AuditLogger::logSecurityEvent('invalid_role_login_attempt', [
                'username' => $login,
                'user_role' => $account->role,
            ]);
            return back()->withErrors([
                'username' => 'Peran akun tidak valid.',
            ])->withInput();
        }

        AuditLogger::logAuthAttempt($login, true);
        Auth::login($account);
        $request->session()->regenerate();

        // Redirect based on user role
        if ($account->role === 'admin') {
            AuditLogger::log('login', 'user', $account->id, [], 'Admin berhasil login');
            return redirect()->intended(route('admin.dashboard'));
        } else {
            AuditLogger::log('login', 'user', $account->id, [], 'User berhasil login');
            return redirect()->intended('/');
        }
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        AuditLogger::log('logout', 'user', $userId, [], 'User berhasil logout');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
