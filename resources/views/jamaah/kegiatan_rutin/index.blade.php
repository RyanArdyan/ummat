{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Kegiatan Rutin')

@section('konten')
<div class="row">
    <div class="col-sm-4">
        <!-- Simple card -->
        <div class="card">
            {{-- cetak asset() berarti panggil folder public --}}
            <img class="card-img-top img-fluid" src="{{ asset('adminto') }}/assets/images/gallery/1.jpg" alt="Card image cap">
            <div class="card-body">
                <h4 class="card-title">Card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make
                    up the bulk of</p>
                <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <!-- Simple card -->
        <div class="card">
            {{-- cetak asset() berarti panggil folder public --}}
            <img class="card-img-top img-fluid" src="{{ asset('adminto') }}/assets/images/gallery/1.jpg" alt="Card image cap">
            <div class="card-body">
                <h4 class="card-title">Card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make
                    up the bulk of</p>
                <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <!-- Simple card -->
        <div class="card">
            {{-- cetak asset() berarti panggil folder public --}}
            <img class="card-img-top img-fluid" src="{{ asset('adminto') }}/assets/images/gallery/1.jpg" alt="Card image cap">
            <div class="card-body">
                <h4 class="card-title">Card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make
                    up the bulk of</p>
                <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <!-- Simple card -->
        <div class="card">
            {{-- cetak asset() berarti panggil folder public --}}
            <img class="card-img-top img-fluid" src="{{ asset('adminto') }}/assets/images/gallery/1.jpg" alt="Card image cap">
            <div class="card-body">
                <h4 class="card-title">Card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make
                    up the bulk of</p>
                <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <!-- Simple card -->
        <div class="card">
            {{-- cetak asset() berarti panggil folder public --}}
            <img class="card-img-top img-fluid" src="{{ asset('adminto') }}/assets/images/gallery/1.jpg" alt="Card image cap">
            <div class="card-body">
                <h4 class="card-title">Card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make
                    up the bulk of</p>
                <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush