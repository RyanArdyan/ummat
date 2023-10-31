{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu admin.layouts.app --}}
@section('title', 'Buat Postingan')

{{-- @dorong('css') berfungsi mendorong value nya ke @stack('css') --}}
@push('css')
    {{-- untuk menggunakan trix editor --}}
    {{-- cetak panggil asset('') berarti memanggil folder public --}}
    {{-- <link rel="stylesheet" href="{{ asset('trix_editor/css/trix_2.0.0.css') }}"> --}}

    {{-- TRIX CSS CDN TERBARU VERSI TERBARU 3 --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css"> --}}

    <style>
    </style>
@endpush

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_tambah">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- is-invalid --}}
                {{-- judul_postingan --}}
                <div class="form-group">
                    <label for="judul_postingan">Judul Postingan<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu judul_postingan --}}
                    <input id="judul_postingan" name="judul_postingan" class="judul_postingan_input input form-control"
                        type="text" placeholder="Masukkan Judul Postingan" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="judul_postingan_error pesan_error text-danger"></span>
                </div>

                <div class="form-group">
                    <label for="kategori_id">Kategori <span class="text-danger"> *</span></label>
                    <br>
                    {{-- name="kategori_id[] agar ketika user memilih banyak kategori maka banyak kategori tersebut akan masuk ke array dari name kategori_id[]" --}}
                    {{-- multiple="" agar aku bisa pilih banyak kategori di element select --}}
                    <select id="kategori_id" name="kategori_id[]" multiple="" class="form-control">
                        {{-- lakukan pengulangan terhadap variable $semua_kategori --}}
                        {{-- variable $kategori berisi setiap value detail_kategori --}}
                        @foreach ($semua_kategori as $kategori)
                            {{-- cetak setiap value variable kategori dan column kategori_id dan column nama_kategori --}}
                            <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <br>
                    {{-- keterangan --}}
                    <span class="text-success bold"><strong>Tekan CTRL + CLICK AGAR BISA PILIH BANYAK
                            KATEGORI.</strong></span>
                    <br>
                    {{-- pesan error --}}
                    <span class="kategori_id_error pesan_error text-danger"></span>
                </div>

                <div class="form-group">
                    <label for="konten_postingan">Konten Postingan<span class="text-danger"> *</span></label>
                    {{-- Aku tidak bisa bersihkan texrarea nya, mungkin emang dari sana nya ada bug dari ckeditor --}}
                    {{-- <textarea id="konten_postingan" class="ckeditor" name="konten_postingan"></textarea> --}}

                    {{-- ini adalah input sebenarnya, jadi aku mengirim value input konten postingan lewat input ini --}}
                    {{-- <input type="hidden" id="konten_postingan" name="konten_postingan"> --}}
                    {{-- ini cuma user interface dari trix editor, input type hidden terhubung dengan trix editor lewat id yaitu konten_postingan --}}
                    {{-- <trix-editor input="konten_postingan"></trix-editor> --}}

                    {{-- terintegrasi dengan package TRIX EDITOR VERSI TERBARU YAITU 5 --}}
                    <textarea name="konten_postingan" id="konten_postingan" cols="30" rows="20"></textarea>
                    <span class="konten_postingan_error pesan_error text-danger"></span>
                </div>

                {{-- gambar_postingan --}}
                <div class="form-group">
                    <label for="pilih_gambar_postingan">Gambar Postingan</label>
                    <br>
                    {{-- asset akan memanggil folder public --}}
                    <img id="pratinjau_gambar_postingan" src="" alt="Gambar postingan" width="150px" height="150px"
                        class="mb-3 rounded">
                    <div class="input-group">
                        <div class="custom-file">
                            <input name="gambar_postingan" type="file"
                                class="input gambar_postingan_input custom-file-input" id="pilih_gambar_postingan">
                            {{-- pesan error --}}
                            <label class="custom-file-label" for="gambar_postingan">Pilih file</label>
                        </div>
                    </div>
                    <span class="pesan_error gambar_postingan_error text-danger"></span>
                </div>

                <div class="form-group">
                    <label for="dipublikasi_pada">Dipublikasi Pada</label>
                    <input type="datetime-local" id="dipublikasi_pada" name="dipublikasi_pada" class="form-control"
                        style="width: 200px">
                    <span class="pesan_error dipublikasi_pada_error text-danger"></span>
                </div>


                <button id="tombol_simpan" type="submit" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Simpan
                </button>
                {{-- cetak panggil route admin.postingan.index --}}
                <a href="{{ route('admin.postingan.index') }}" class="btn btn-sm btn-danger">
                    <i class="mdi mdi-arrow-left">
                        Kembali
                    </i>
                </a>
            </form>
        </div>
    </div>
@endsection


{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
    {{-- CKEditor adalah editor teks kaya WYSIWYG yang memungkinkan penulisan konten langsung di dalam halaman web atau aplikasi online.  --}}
    {{-- <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script> --}}

    {{-- TRIX JS VRESI TERBARU YAITU 3 --}}
    {{-- <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script> --}}

    {{-- CKEditor versi 5, CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        // start of the WYSIWYG feature along with image upload using CKEditor 5
        // my upload adapter class
        class MyUploadAdapter {
            // contractor method, there is 1 parameter to receive arguments
            constructor(loader) {
                // This file loader instance is used during file upload
                // call the external loader variable and then fill in the loader parameter values
                this.loader = loader;
            }

            // asynchronous programming code
             // start the upload process
             // This upload method is called when CKEditor asks to upload a file. Returns a promise that performs a file upload using HMLHttpRequest
            upload() {
                // asynchronous programming operations
                 // return the loader object, outside file properties
                return this.loader.file
                    // use then on promises
                     // after the file is loaded (resolve), a new promise will be loaded, run the following function and return resolve if successful and reject if rejected
                    .then(file => new Promise((resolve, reject) => {
                        // initializeExternal request
                        this._initRequest();
                        // initialize external Listener then pass 3 arguments
                        this._initListeners(resolve, reject, file);
                        // sendRequest from file
                        this._sendRequest(file);
                    }));
            }


            // deny the upload process 
            // to reject the upload process if necessary
            abort() {
                // if there is an xhr variable that is outside
                if (this.xhr) {
                    // call the xhr object, the outer reject method
                    this.xhr.abort();
                }
            }

            // initialize the XMLHttpRequest object using the URL passed to the constructor
            _initRequest() {
                // contains the xjr variable which is outside and then filled in by creating an object from XMLHttpRequest()
                const xhr = this.xhr = new XMLHttpRequest();

                // note that requests may look different. This is up to you and your editor.
                 // integration to select the correct communication channel. This example uses a SEND request with JSON as a data structure but the configuration must be different.
                 // asynchronous programming, namely carrying out tasks without queuing so you can do tasks simultaneously
                 // this is ajax, open a POST type route, call the following url, true means this request is asynchronous.
                xhr.open('POST', '/admin/postingan/upload-gambar', true);
                // must create security from CSRF attacks
                xhr.setRequestHeader('x-csrf-token', "{{ csrf_token() }}");
                // WE EXPECT THE TYPE OF RESPONSE SENT FROM THE SERVER TO BE JSON.
                xhr.responseType = 'json';
            }

            // THERE ARE THREE PARAMETERS, NAMELY RESOLVE, REJECT AND FILE
            _initListeners(resolve, reject, file) {
                // CONTAINTS THE XHR THAT IS OUTSIDE
                const xhr = this.xhr;
                // CONTAINTS THE loader THAT IS OUTSIDE
                const loader = this.loader;
                // CONSTAINS UNABLE TO UPLOAD TO FILE, CALL OBJECT FILE, PROPERTY NAME
                const genericErrorText = `Unable to upload file: ${ file.name }.`;

                // object xhr, add an error listener then execute the function, call the reject method, then send the value of the genericErrorText variable
                xhr.addEventListener('error', () => reject(genericErrorText));
                xhr.addEventListener('abort', () => reject());
                xhr.addEventListener('load', () => {
                    // containts xhr object, response property
                    const response = xhr.response;

                    // This example assumes the XHR object server response will come with an error that has its own message that can be passed to the reject method in the upload promise
                    //
                    // it is done properly. The reject() function must be called when the upload fails.
                    // if there is no response or there is a response object.property error
                    if (!response || response.error) {
                        // return the reject method, do conditioning, if there is a response and the response is an error then send response.error.message, if not then send generateErrorText
                        return reject(response && response.error ? response.error.message : genericErrorText);
                    }

                    // UploadAdapter#upload documentation, points to the image on the server.
                     // this url is used to display images in the content. Delve deeper into the UploadAdapter#upload documentation.



                    // if the upload is complete, complete the upload promise with an object containing at least the default url,
                    resolve({
                        // The default contains a response object, property url
                        default: response.url
                    });
                });

                // property that the example uses to display the upload progress bar in the user editor interface
                // if there is an xhr object, the upload property then
                if (xhr.upload) {
                    // xhr.upload.Add Event Listener, run the following function and there is an evt parameter
                    xhr.upload.addEventListener('progress', evt => {
                        // if there is a computable event.length
                        if (evt.lengthComputable) {
                            // loader.totalUpload contains object evt, property total
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    });
                }
            }

            // prepare the data and send the request
            // send Request, there is 1 parameter namely file
            _sendRequest(file) {
                // prepare data form
                // contains the initialization of the FormData() object
                const data = new FormData();

                // object data.add or send the saved image file using the upload name
                // assume there is input type="file", name="upload", then it is sent
                data.append('upload', file);

                // important note: this is the correct place to implement mechanisms such as authentication and csrf protection. For instantiation, you can use XMLHttpRequest.setRequestHead() to set the request header containing a csrf token that is easily generated from your application.

                // send request
                // call the xhr object, then send the data property
                this.xhr.send(data);
            }
        }

        // function PluginAdapterUploaderMyCustomizer, there is 1 parameter
        function MyCustomUploadAdapterPlugin(editor) {
            // editor.plugins.get fileRepository.createAdapterUpload contains run the following function and there is 1 parameter
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                // configure the url to the upload script in the backend here!
                // return the initialization of the MyUploadAdapter function and then send the loader parameter value
                return new MyUploadAdapter(loader);
            };
        }

        // use the CKEditor version 5 package to use the WYSIWYG feature
        // editorKlasik
        ClassicEditor
        // .create, document.getElementBypost_contentId
        .create(document.getElementById("konten_postingan"), {
            // extra plugin2x contains array
            extraPlugins: [
                // contains the PluginAdapterUploaderMyCustomization variable
                MyCustomUploadAdapterPlugin
            ],

            // other optional configurations
            // 
        })
        // if there is an error then capture the error in the error parameter then run the following function
        .catch((error) => {
            // print the value of the error parameter
            console.log(error);
        });
        // end of WYSIWYG feature along with image upload using CKEditor

        // Mengecek apakah ada kategori, jika tidak ada kategori maka arahkan ke url /postingan/create
        // Jika document siap maka jalankan fungsi berikut
        $(document).ready(function() {
            // jquery lakukan ajax
            $.ajax({
                    // url panggil route admin.postingan.cek_apakah_ada_kategori
                    "url": "{{ route('admin.postingan.cek_apakah_ada_kategori') }}",
                    // panggil route tipe kirim
                    "type": "GET"
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                .done(function(resp) {
                    // jika value resp sama dengan "Anda harus membuat setidaknya satu kategori terlebih dahulu."
                    if (resp === "Anda harus membuat setidaknya satu kategori terlebih dahulu.") {
                        // tampilkan notifikasi menggunakan package sweetalert
                        Swal.fire({
                                icon: 'error',
                                title: 'Oops',
                                // berisi value parameter resp
                                text: resp,
                            })
                            // kemudian hasilnya maka jalankan fungsi berikut dan ambil hasil nya
                            .then((result) => {
                                // jika aku click oke pada pop up sweetalert maka
                                // jika hasilnya dikonfirmasi maka
                                if (result.isConfirmed) {
                                    // pindahkan ke route admin.kategori.buat
                                    // jendela.lokasi.href
                                    window.location.href = "{{ route('admin.kategori.create') }}"
                                };
                            });
                    };
                });
        });

        // tampilkan pratinjau gambar ketika user mengubah gambar
        // jika #pilih_gambar_postingan diubah maka jalankan fungsi berikut
        $("#pilih_gambar_postingan").on("change", function() {
            // ambil gambarnya, this berarti #pilih_gambar_postingan, index ke 0
            let gambar = this.files[0];
            // jika ada gambar yang di pilih
            if (gambar) {
                // berisi baru FilePembaca
                let filePembaca = new FileReader();
                // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
                filePembaca.onload = function(e) {
                    // panggil #pratinjau_gambar_postingan lalu pangil attribute src diisi dengan acara.target.hasil
                    $("#pratinjau_gambar_postingan").attr("src", e.target.result);
                };
                // new FileReader() baca data sebagai url dari this.file[0]
                filePembaca.readAsDataURL(gambar);
            };
        });

        // jika formulir tambah dikirim
        // jika #form_tambah dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_tambah").on("submit", function(e) {
            // cegah bawaannya yaitu reload
            e.preventDefault();

            // jquery, lakukan ajax
            $.ajax({
                    // url ke route admin.postingan.store
                    url: "{{ route('admin.postingan.store') }}",
                    // panggil route kirim
                    type: "POST",
                    // kirimkan data dari #form_data, otomatis membuat objek atau {}
                    data: new FormData(this),
                    // aku butuh 3 baris kode berikut, kalau membuat objek secara manual maka tidak butuh 3 baris kode berikut
                    // prosesData: salah,
                    processData: false,
                    contentType: false,
                    cache: false,
                    // sebelum kirim, hapus validasi error dulu
                    // sebelum kirim, jalankan fungsi berikut
                    beforeSend: function() {
                        // panggil .input lalu hapus .is-invalid
                        $(".input").removeClass("is-invalid");
                        // panggil .pesan_error lalu kosongkan textnya
                        $(".pesan_error").text("");
                    }
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                .done(function(resp) {
                    // jika validasi menemukan error
                    // jika resp.status sama dengan 0
                    if (resp.status === 0) {
                        // cetak value dari tanggapan.kesalahan
                        // console.log(resp.errors);
                        // lakukan pengulangan
                        // key berisi semua nilai attribute name misalnya judul_postingan
                        // value berisi array yang menyimpan semua pesan error misalnya "Judul Postingan Harus Diiisi"
                        // jquery.setiap(tanggapan.kesalahan2x, fungsi(kunci, nilai))
                        $.each(resp.errors, function(key, value) {
                            // contohnya panggil .judul_postingan_input lalu tambah class is-invalid
                            $(`.${key}_input`).addClass("is-invalid");
                            // contohnya panggil .judul_postingan_error lalu isi textnya dengan pesan error
                            $(`.${key}_error`).text(value[0]);

                        });
                    }
                    // jika berhasil menyimpan postingan
                    // lain jika resp.status sama dengan 200
                    else if (resp.status === 200) {
                        // reset formulir
                        // panggil #form_tambah index ke 0 lalu atur ulang semua input
                        $("#form_tambah")[0].reset();
                        // reset value dari input trix editor
                        // $("#konten_postingan").val("");

                        // reset pratinjau gambar
                        // jquery panggil #pratinjau_gambar_postingan, lalu attribute src, value nya di kosongkan pake ""
                        $("#pratinjau_gambar_postingan").attr("src", "");
                        // Judul Postingan di focuskan
                        // panggil #judul_postingan lalu focuskan
                        $("#judul_postingan").focus();
                        // notifikasi
                        // panggil toastr tipe sukses dan tampilkan pesannya menggunakan value dari tanggapan.pesan
                        toastr.success(`Berhasil menyimpan.`);
                    };
                });
        });
    </script>
@endpush
