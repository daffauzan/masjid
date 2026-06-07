<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\user as User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_telp' => $validated['no_telp'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            AuditLogger::log('register', 'user', $user->id, [], 'User baru berhasil registrasi');

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('user.assessment.create')
                ->with('success', 'Registrasi berhasil. Silakan lanjutkan assessment zakat Anda.');
        } catch (\Exception $e) {
            AuditLogger::logSecurityEvent('registration_error', [
                'email' => $validated['email'],
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'registration' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
            ])->withInput();
        }
    }
}
