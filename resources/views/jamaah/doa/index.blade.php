{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Doa')


{{-- @push berfungsi mendorong value nya lalu nanti ditangap oleh @stack('css') --}}
@push('css')
{{-- cetak panggil, asset berarti panggil public --}}
<link rel="stylesheet" href="{{ asset('css_saya/style.css') }}">
@endpush

{{-- kirimkan value @bagian('konten') lalu nanti akan ditangkap oleh @yield('konten') --}}
@section('konten')
<div class="row">
    
</div>
@endsection

@push('script')
<script>
    // jika document siap maka jalankan fungsi berikut
    $(document).ready(function() {


            $.ajax({
                url: 'https://doa-doa-api-ahmadramadhan.fly.dev/api',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                type: "GET", /* or type:"GET" or type:"PUT" */
                dataType: "json",
                data: {
                },
                success: function (result) {
                    console.log(result);
                },
                error: function () {
                    console.log("error");
                }
            });
    });
</script>
@endpush