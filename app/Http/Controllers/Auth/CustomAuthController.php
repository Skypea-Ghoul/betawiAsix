<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return response()->json([
                    'message' => 'Login successful',
                    'user' => Auth::user()->only('id', 'name', 'email'),
                ], 200);
            }

            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Check if the user is authenticated.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAuth()
    {
        if (Auth::check()) { // Auth::check() akan memverifikasi apakah ada pengguna yang login dalam sesi
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user()->only('id', 'name', 'email'), // Kirim data user yang terbatas
            ], 200);
        } else {
            return response()->json([
                'authenticated' => false,
            ], 200); // Penting: Kirim status 200 OK meskipun tidak authenticated
                      // agar frontend tidak menganggapnya sebagai error jaringan
        }
    }

    
  public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
            // Validasi hanya email Google/gmail
            function ($attribute, $value, $fail) {
                if (!preg_match('/@gmail\.com$/', $value)) {
                    $fail('Email harus menggunakan akun Gmail.');
                }
            }
        ],
        'password' => 'required|string|min:8|confirmed',
    ]);

    // (Opsional) Untuk validasi lebih kuat, gunakan Google API untuk cek apakah email benar-benar akun Google.

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    Auth::login($user);

    return response()->json(['success' => true]);
}

    public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

public function handleGoogleCallback()
{
     try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Jika belum ada, daftarkan otomatis
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                // Buat password random karena tidak digunakan untuk login manual
                'password' => bcrypt(uniqid()), 
            ]);
        }

        Auth::login($user, true);
        return redirect('/')->with('success', 'Login berhasil!');
        
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
    }
}
}
