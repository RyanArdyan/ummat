{{-- looping semua_komentar --}}
{{-- untuksetiap semua_komentar sebagai $komentar jadi variable $komentar berisi setiap baris komentar --}}
@foreach($semua_komentar as $komentar)
    {{-- Jika value detail_komentar, column parent_id sama dengan null berarti ada isinya dan dia merupakan komentar balasan maka berikan margin-left: 40px  --}}
    <div class="display-comment" @if($komentar->parent_id != null) style="margin-left:40px;" @endif>
        {{-- cetak penulis komentar --}}
        {{-- cetak value detail_komentar yang berelasi dengan models user, column name --}}
        <strong>{{ $komentar->user->name }}</strong>
        {{-- jika dia merupakan komentar balasan maka --}}
        {{-- cetak value detail_komentar, column komentarnya --}}
        <p>{{ $komentar->komentarnya }}</p>
        {{-- panggil route tipe kirim, panggil route yang bernama postingan.simpan_komentar --}}
        <form method="post" action="{{ route('postingan.simpan_komentar') }}">
            {{-- laravel mewajibkan keamanan dari serangan csrf --}}
            @csrf
            <div class="form-group">
                <input type="text" name="komentarnya" class="form-control" />
                {{-- cetak value variable $postingan_id --}}
                <input type="hidden" name="postingan_id" value="{{ $postingan_id }}" />
                {{-- cetak value detail_komentar, column komentar_id --}}
                <input type="hidden" name="parent_id" value="{{ $komentar->komentar_id }}" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-warning">Balas</button>
            </div>
        </form>
        {{-- termasuk atau panggil jamaah.postingan.komentar, kirimkan data berupa array --}}
        @include('jamaah.postingan.komentar', [
            // key semua_komentar berisi value models/komentar, method balasan agar mengambil semua komentar balasan
            'semua_komentar' => $komentar->balasan
        ])
    </div>
@endforeach  