<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidasiNomorWhatsapp implements ValidationRule
{
    /**
     * Jalankan aturan validasi
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // publik fungsi validasi, string $attribute, campuran $nilai, penutup $gagal
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // berisi value input nomor_wa
        $value_input_nomor_wa = $value;
        // jika value parameter $value atau misalnya value innput name="nomor_wa" tidak dimulai dari "62"
        if (substr($value_input_nomor_wa, 0, 2) !== "62") {
            // $gagal lalu kirimkan pesan misalnya 'Input nomor wa harus dimuali dari 62'
            $fail('Input :attribute harus dimulai dari 62');
        };

    }
}

