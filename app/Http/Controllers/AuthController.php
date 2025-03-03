<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User1;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // **Login**
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Ambil user berdasarkan username
        $user = User1::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);

            // Redirect berdasarkan role
            if ($user->isSuperADM()) {
                return redirect('/users');
            } elseif ($user->isAdmin()) {
                return redirect('/surat/admin/dashboard');
            } elseif ($user->isDirektur()) {
                return redirect('/users');
            } elseif ($user->isDM()) {
                return redirect('/dashboard/marketing');
            } elseif ($user->isIC()) {
                return redirect('/users');
            } elseif ($user->isWRH()) {
                return redirect('/dashboard/warehouse');
            } elseif ($user->isFNC()) {
                return redirect('/surat/finance/dashboard');
            }elseif ($user->isPCH()) {
                return redirect('/dashboard/purchasing');
            } elseif ($user->isEks()) {
                return redirect('/users');
            } elseif ($user->isCS()) {
                return redirect('/users');
            } elseif ($user->isTeknisi()) {
                return redirect('/users');
            } else {
                return redirect('/users');
            }
        }

        return response()->json(['message' => 'Username atau password salah'], 401);
    }

    // **Logout**
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('message', 'Logout berhasil');
    }
}
