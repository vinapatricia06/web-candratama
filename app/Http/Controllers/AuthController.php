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
                return redirect('/users'); //sudah
            } elseif ($user->isAdmin()) {
                return redirect('/surat/admin/dashboard'); //sudah
            } elseif ($user->isCEO()) {
                return redirect('/dashboard/CEO'); //sudah
            } elseif ($user->isDM()) {
                return redirect('/dashboard/marketing'); //sudah
            } elseif ($user->isIC()) {
                return redirect('/users');
            } elseif ($user->isWRH()) {
                return redirect('/surat/warehouse/dashboard'); 
            } elseif ($user->isFNC()) {
                return redirect('/surat/finance/dashboard'); //kurang dashboard
            }elseif ($user->isPCH()) {
                return redirect('/surat/purchasing/dashboard');
            } elseif ($user->isEks()) {
                return redirect('/users');
            } elseif ($user->isCS()) {
                return redirect('/users');
            } elseif ($user->isTeknisi()) {
                return redirect('/users');
            } else {
                abort(403, 'Akses tidak diizinkan');
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

    

    public function dashboardCEO()
    {
        // Ambil user yang sedang login
        $user = Auth::user(); // Mengambil data pengguna yang sedang login
        
        // Ambil nama direktur dari data user
        $directorName = $user->nama; // Asumsi nama kolom di tabel users1 adalah 'nama'

        // Format waktu saat ini
        $dateTime = now()->format('l, d M Y H:i');  // Menampilkan tanggal dan waktu
        
        return view('auth.dashboardCEO', compact('directorName', 'dateTime'));
    }
}
