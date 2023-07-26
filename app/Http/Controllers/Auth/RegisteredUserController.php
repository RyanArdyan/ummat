<?php

namespace App\Http\Controllers\Auth;

// gunakan atau panggil
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
// validasi khusus yang aku buat sendiri, jadi user hanya boleh memasukkan akun gmail
use App\Rules\GmailRule;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman registrasi
     */
    public function create(): View
    {
        // kembalikkan ke tampilan auth.registrasi
        return view('auth.registrasi');
    }

    // $request berisi data-data formulir atau value input attribute name="" yang dikirim oleh script
    public function store(Request $request)
    {
        // validasi untuk semua input yang punya attribute name
		$validator = Validator::make($request->all(), [
            // value input name="name" harus mengikut aturan berikut
            'name' => ['required', 'unique:users', 'string', 'min:3', 'max:50'],
            // unique berarti tidak boleh sama
            // new GmailRule berarti user hanya boleh memasukkan akun gmail
            'email' => ['required', new GmailRule, 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'min:6', 'max:20']
		]);

		// jika validasi biasa gagal
		if ($validator->fails()) {
            // kembalikkan tanggapan berupa json yang berisi array assosiatif ke javascript
			return response()->json([
                // key status berisi value 0
				'status' => 0,
                // key message berisi pesan 'Validasi Biasa Errors'
				'message' => 'Validasi Biasa Errors',
				// errors akan berisi semua value attribute name yang error dan pesan errornya
				'errors' => $validator->errors()
			]);
		};

        // simpan user
        // user::buat([])
        $user = User::create([
            // column nama berisi value input name="nama"
            'name' => $request->name,
            // column email berisi value input name="email"
            'email' => $request->email,
            // column password berisi value input name="password" yang sudah di hash
            // hash::buat
            'password' => Hash::make($request->password),
        ]);

        // acara(baru registrasi($pengguna))
        event(new Registered($user));

        // autentikasi::masuk($pengguna)
        Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);

        // kembalikkan tanggapan berupa json lalu kirimkan data
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key message berisi value berikut
            'message' => 'Berhasil Registrasi'
        ]);
    }
}
