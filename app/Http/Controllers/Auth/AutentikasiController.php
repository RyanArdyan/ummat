<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Rules\GmailRule;

class AutentikasiController extends Controller
{
    // tampilan formulir login
    // publik fungsi index
    public function index()
    {
        // kembalikan ke tampilan autentikasi.login
        return view('auth.login');
    }

    // logic login
    public function store(Request $request)
	{
        // validasi untuk semua input yang punya attribute name
		// berisi validasi::buat($permintaan->semua)
		$validator = Validator::make($request->all(), [
            // input name email harus mengikut aturan berikut
			// new GmailRule berarti inisialisasi GmailRule.php lalu panggil GmailRule lalu user harus menulis @gmail.com
			'email' => ['required', new GmailRule],
			// value input password: wajib diisi, minimal 6, maksimal 20
			'password' => ['required', 'min:6', 'max:20']
		]);

		// jika validasi biasa gagal
		if ($validator->fails()) {
            // kembalikkan response berupa json ke javascript
			return response()->json([
				// key status berisi value 0
				'status' => 0,
				// key pesan berisi value string berikut
				'message' => 'Validasi Biasa Errors',
				// key kesalahan2x akan berisi semua value attribute name yang error misalnya email dan password dan pesan errornya misalnya "Email Wajib Diisi."
				'errors' => $validator->errors()
			]);
		};

		// jika validasi lolos maka cek apakah email dan password yang di input ada di database
        // argument kedua attempt yaitu true berarti fitur ingat saya true secara default
		// jika (autentikasi::mencoba(['emal' => $permintaan->email, 'password' => $permintaan->password], benar))
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
            // buat ulang sessi
			// $permintaan->sesi()->hasilkan ulang
			$request->session()->regenerate();

			// ambil value column name dari user yang login
			// berisi model user dimana value column email sama dengan value input name="email" maka ambil value column name nya
			$name = User::where('email', $request->email)->value('name');
			// ambil value column adalah_admin dari user yang login
			// berisi model user dimana value column email sama dengan value input name="emai", maka ambil value column is_admin
			$is_admin = User::where('email', $request->email)->value('is_admin');

			// kembalikkan tanggapan berupa json lalu kirimkan array
			return response()->json([
				// key status berisi value 200
				'status' => 200,
				// key pesan berisi string berikut
				'message' => 'User Berhasil Login',
				// aku mengirim 1 atau 0 dalam bentuk type string awoakwaok
				// key adalah_admin berisi value variable is_admin
				'is_admin' => $is_admin,
				// key name berisi value variable nama
				'name' => $name
			]);
		}; 

		// lakukan pengulangan terdapap semua data di table users
        // jika validasi email dan password tidak lolos maka lakukan fitur password salah yaitu jika email yang dimasukkan benar tapi password nya salah maka tampilkan validasi error
		// untuk_setiap(pengguna::semua sebagai $detail_pengguna)
        foreach(User::all() as $detail_user) {
            // jika value input name="email" yang dimasukkan sama dengan detail user, column email
            if ($request->email === $detail_user->email) {
                // jika password yang dimasukkan tidak sama dengan detail_user, column password
                if (!Hash::check($detail_user->password, $request->password)) {
                    // kembalikkan tanggapan berupa json
                    return response()->json([
						// key pesan berisi string berikut
                        'message' => 'Password salah.'
                    ]);
                };
            };
        };

		// ambil value column is_admin dari user yang login
		// berisi model user dimana value column email sama dengan value input name="email" maka ambil value column is_admin nya
		$is_admin = User::where('email', $request->email)->value('is_admin');

        // jika email yang di input tidak ada di table users.
        // kembalikkan tanggapan berupa json
        return response()->json([
			// key status berisi value 0
            'status' => 0,
			// key pesan berisi string berikut
            'message' => 'Email belum terdaftar',
			
            // aku harus mengirim value column is_admin dari user yang login agar ketika admin yang login maka dia akan diarahkan ke url /dashboard dan di halaman dashboard, dan jika dia adalah user maka dia akan diarahkan ke url / dan di halaman home.
            // key adalah_admin berisi value detail_user yang login, column is_admin
            'is_admin' => $is_admin
        ]);
	}

    // Logout akan mengarahkan user ke halaman frontend.home.index
    public function logout()
	{
		// autentikasi, keluar
		Auth::logout();
		// sesi, batalkan
		session()->invalidate();
		// sesi, buat ulang token
		session()->regenerateToken();
        // kembali alihkan ke route frontend.index
		return redirect()->route('frontend.index');
	}
}
