@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    <style>
        .colored-toast.swal2-icon-success {
            background-color: #a5dc86 !important;
        }

        .colored-toast.swal2-icon-error {
            background-color: #f27474 !important;
        }

        .colored-toast.swal2-icon-warning {
            background-color: #f8bb86 !important;
        }

        .colored-toast.swal2-icon-info {
            background-color: #3fc3ee !important;
        }

        .colored-toast.swal2-icon-question {
            background-color: #87adbd !important;
        }

        .colored-toast .swal2-title {
            color: white;
        }

        .colored-toast .swal2-close {
            color: white;
        }

        .colored-toast .swal2-html-container {
            color: white;
        }

        .chart {
            width: 100%;
        }

        .activities {
        display: flex;
        flex-wrap: wrap;
        }
        .activities .activity {
        width: 100%;
        display: flex;
        position: relative;
        }
        .activities .activity:before {
        content: " ";
        position: absolute;
        left: 25px;
        top: 0;
        width: 2px;
        height: 100%;
        background-color: #6777ef;
        }
        .activities .activity:last-child:before {
        display: none;
        }
        .activities .activity .activity-icon {
            width: 50px;
            height: 50px;
            border-radius: 3px;
            line-height: 50px;
            font-size: 20px;
            text-align: center;
            margin-right: 20px;
            border-radius: 50%;
            flex-shrink: 0;
            text-align: center;
            z-index: 1;
        }
        .activities .activity .activity-detail {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            position: relative;
            padding: 15px;
        }
        .activities .activity .activity-detail:before {
            content: "";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            font-size: 20px;
            position: absolute;
            left: -8px;
            color: #fff;
        }
        .activities .activity .activity-detail h4 {
            font-size: 18px;
            color: #191d21;
        }
        .activities .activity .activity-detail p {
            margin-bottom: 0;
        }

        @media only screen and(max-width: 720px) {
            .chart {
                width: 100%;
                height: 1000px;
            }
            .activities .activity i {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>@yield('title')</h1>
    </div>

    <div class="section-body">
        @if (session()->has('welcome'))
            <div class="alert alert-primary alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <b>Selamat Datang {{session()->get('welcome')}}</b>
                </div>
            </div>
        @endif
        @if (Auth::user()->role == 'sekolah' && Auth::user()->bio == null)
            <form class="wizard-content mt-2">
                <div class="wizard-pane">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Sekolah {{Auth::user()->username}}</h4>
                        </div>
                        <div class="card-body">
                            {{-- tab 1 --}}
                            <div class="tab">
                                <div class="row">
                                    <div class="tab"></div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Nama Sekolah</label>
                                            <input type="text" value="{{Auth::user()->username}}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Email Sekolah</label>
                                            <input type="text" value="{{Auth::user()->email}}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">Status Sekolah</label>
                                            <select id="status_sekolah" class="form-control">
                                                <option value="">- Pilih -</option>
                                                <option value="Negeri">Negeri</option>
                                                <option value="Swasta">Swasta</option>
                                            </select>
                                            <span class="text-danger invalid-feedback-status_sekolah"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Kepala Sekolah</label>
                                            <input type="text" id="kepsek" class="form-control">
                                            <span class="text-danger invalid-feedback-kepsek"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">NPSN</label>
                                            <input type="text" id="npsn" class="form-control">
                                            <span class="text-danger invalid-feedback-npsn"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">NSS</label>
                                            <input type="text" id="nss" class="form-control">
                                            <span class="text-danger invalid-feedback-nss"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Akreditasi</label>
                                            <select id="akreditasi" class="form-control">
                                                <option value="">- Pilih -</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                            <span class="text-danger invalid-feedback-akreditasi"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Sekolah Lainnya</h4>
                        </div>
                        <div class="card-body">
                            {{-- tab 2 --}}
                            <div class="tab">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Provinsi</label>
                                            <select class="form-control" id="province_id">
                                                <option value="">- Pilih -</option>
                                                @foreach ($province as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger invalid-feedback-province_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Kab/Kota</label>
                                            <select class="form-control" id="regencie_id">
                                            </select>
                                            <span class="text-danger invalid-feedback-regencie_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Kecamatan</label>
                                            <select class="form-control" id="district_id">
                                            </select>
                                            <span class="text-danger invalid-feedback-district_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Desa</label>
                                            <select class="form-control" id="village_id">
                                            </select>
                                            <span class="text-danger invalid-feedback-village_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Alamat Sekolah</label>
                                            <textarea id="alamat" class="form-control" cols="30" rows="10"></textarea>
                                            <span class="text-danger invalid-feedback-alamat"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-md-right text-left">Logo Sekolah / Foto Sekolah</label>
                                            <input type="file" id="avatars" class="form-control">
                                            <span class="text-danger invalid-feedback-avatars"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="javascript:void(0)" id="store" class="btn btn-icon icon-right btn-primary mt-4" style="float: right">Lengkapi Data <i class="fas fa-paper-plane"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @elseif(Auth::user()->role == "petugas" || Auth::user()->role == "admin")
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Petugas</h4>
                            </div>
                            <div class="card-body">
                                {{$petugas}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Sekolah</h4>
                            </div>
                            <div class="card-body">
                                {{$sekolah}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Arsip</h4>
                            </div>
                            <div class="card-body">
                                {{$arsip}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah File</h4>
                            </div>
                            <div class="card-body">
                                {{$file}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Jumlah Sekolah Negri & Swasta</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                {!! $chart->container() !!}
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Pemberitahuan</h4>
                        </div>
                        <div class="card-body bg-secondary" id="top-5-scroll">
                        </div>
                        <div class="card-footer">
                            <div class="form-group">
                                <form action="#" class="novalidate">
                                    <textarea id="editor" class="form-control" cols="30" rows="10"></textarea>
                                </form>
                                <span class="invalid-feedback-description text-danger"></span>
                            </div>
                            <button class="btn btn-primary mt-2 float-right" id="kirim">Kirim</button>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->role == 'sekolah')
             <div class="card card-primary">
                <div class="card-body bg-secondary">
                    <div id="append-html"></div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <form action="#" class="novalidate">
                            <textarea id="editor" class="form-control" cols="30" rows="10"></textarea>
                        </form>
                        <span class="invalid-feedback-description text-danger"></span>
                    </div>
                    <button class="btn btn-primary mt-2 float-right" id="kirim">Kirim</button>
                </div>
             </div>
        @endif
    </div>
  </section>
@endsection

@push('js')
    <script src="{{asset('js/multi_select_form.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    {!! $chart->script() !!}
    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'white',
                customClass: {
                    popup: 'colored-toast'
                },
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            })

            let editor;
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .then( newEditor => {
                    editor = newEditor;
                } )
                .catch( error => {
                    console.error( error );
                });

            getJsonResponse();

            $("#kirim").click(function(e) {
                e.preventDefault();
                let description = editor.getData();
                $.ajax({
                    url: '/pemberitahuan',
                    type: 'POST',
                    data: {
                        description: description
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('.invalid-feedback-' + key).html(value);
                                setTimeout(() => {
                                    $('#' + key).removeClass('is-invalid');
                                    $('.invalid-feedback-' + key).html("");
                                }, 2000);
                            });
                        } else {
                            getJsonResponse();
                            $(".novalidate")[0].reset();
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: '/pemberitahuan/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        getJsonResponse();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            });

            function getJsonResponse() {
                $.ajax({
                    url: '/pemberitahuan',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let html = '';
                        if (response.role == "admin" || response.role == "petugas") {
                            $.each(response.data, function(key, value) {
                                if (response.users_id == value.users_id) {
                                    html += `<div class="activities">
                                            <div class="activity">
                                                <div class="activity-icon bg-primary text-white shadow-primary">
                                                    <i class="fas fa-comments"></i>
                                                </div>
                                                <div class="activity-detail">
                                                    <div class="mb-2">
                                                        <a class="text-job" href="javascript:void(0)">${value.user.username}</a>
                                                        <span class="bullet"></span>
                                                        <span class="text-job text-primary">${value.tanggal}</span>
                                                        <div class="float-right dropdown">
                                                            <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                                            <div class="dropdown-menu">
                                                                <div class="dropdown-title">Options</div>
                                                                <a href="javascript:void(0)" data-id="${value.id}" id="delete" class="dropdown-item has-icon text-danger"><i class="fas fa-trash"></i> Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>${value.description}</p>
                                                </div>
                                            </div>
                                        </div>`;
                                } else {
                                    html += `<div class="activities">
                                            <div class="activity">
                                                <div class="activity-icon bg-primary text-white shadow-primary">
                                                    <i class="fas fa-comments"></i>
                                                </div>
                                                <div class="activity-detail">
                                                    <div class="mb-2">
                                                        <a class="text-job" href="javascript:void(0)">${value.user.username}</a>
                                                        <span class="bullet"></span>
                                                        <span class="text-job text-primary">${value.tanggal}</span>
                                                        <div class="float-right dropdown">
                                                            <div class="dropdown-menu">
                                                                <div class="dropdown-title">Options</div>
                                                                <a href="javascript:void(0)" data-id="${value.id}" id="delete" class="dropdown-item has-icon text-danger"><i class="fas fa-trash"></i> Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>${value.description}</p>
                                                </div>
                                            </div>
                                        </div>`;
                                }
                            });
                            $("#top-5-scroll").html(html);
                        } else {
                            $.each(response.data, function(key, value) {
                                if (response.users_id == value.users_id) {
                                    html += `<div class="activities">
                                            <div class="activity">
                                                <div class="activity-icon bg-primary text-white shadow-primary">
                                                    <i class="fas fa-comments"></i>
                                                </div>
                                                <div class="activity-detail">
                                                    <div class="mb-2">
                                                        <a class="text-job" href="javascript:void(0)">${value.user.username}</a>
                                                        <span class="bullet"></span>
                                                        <span class="text-job text-primary">${value.tanggal}</span>
                                                        <div class="float-right dropdown">
                                                            <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                                            <div class="dropdown-menu">
                                                                <div class="dropdown-title">Options</div>
                                                                <a href="javascript:void(0)" data-id="${value.id}" id="delete" class="dropdown-item has-icon text-danger"><i class="fas fa-trash"></i> Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>${value.description}</p>
                                                </div>
                                            </div>
                                        </div>`;
                                } else {
                                    html += `<div class="activities">
                                            <div class="activity">
                                                <div class="activity-icon bg-primary text-white shadow-primary">
                                                    <i class="fas fa-comments"></i>
                                                </div>
                                                <div class="activity-detail">
                                                    <div class="mb-2">
                                                        <a class="text-job" href="javascript:void(0)">${value.user.username}</a>
                                                        <span class="bullet"></span>
                                                        <span class="text-job text-primary">${value.tanggal}</span>
                                                        <div class="float-right dropdown">
                                                            <div class="dropdown-menu">
                                                                <div class="dropdown-title">Options</div>
                                                                <a href="javascript:void(0)" data-id="${value.id}" id="delete" class="dropdown-item has-icon text-danger"><i class="fas fa-trash"></i> Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>${value.description}</p>
                                                </div>
                                            </div>
                                        </div>`;
                                }
                            });
                            $("#append-html").html(html);
                        }
                    },
                    errorr: function(err) {
                        console.log(err);
                    }
                })
            }
        });
    </script>
@endpush
