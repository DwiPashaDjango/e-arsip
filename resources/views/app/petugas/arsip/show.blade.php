@extends('layouts.app')

@section('title')
    {{$data[0]->title}}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
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

        #myProgress {
            width: 100%;
            background-color: rgb(220, 218, 218);
            border-radius: 5px
        }

        #myBar {
            width: 3%;
            height: 30px;
            background-color: #377ad8 !important;
            text-align: center;
            line-height: 30px;
            color: white;
            border-radius: 5px
        }

        #myProgressSave {
            width: 100%;
            background-color: rgb(220, 218, 218);
            border-radius: 5px
        }

        #myBarSave {
            width: 3%;
            height: 30px;
            background-color: #377ad8 !important;
            text-align: center;
            line-height: 30px;
            color: white;
            border-radius: 5px
        }

        .input-group-prepend {
            cursor: pointer;
        }

        @media only screen and(max-width: 720px) {
            tbody tr td {
                text-align: left;
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
        @if (Auth::user()->role == 'sekolah')
            @php
                $batas = \Carbon\Carbon::parse($data[0]->end_date);
                $now = \Carbon\Carbon::now();
                $title = $data[0]->title;
                if ($now->gt($batas)) {
                    $selisih = $now->diff($batas)->format('%d hari %h jam %i menit %s detik');
                    $message = "Batas waktu pengumpulan file {$title} telah habis. Waktu habis sudah lewat sejak {$selisih} yang lalu.";
                    $class = "danger";
                } else {
                    $selisih = $now->diff($batas)->format('%d hari %h jam %i menit %s detik');
                    $message = "Anda masih memiliki waktu {$selisih} untuk mengumpulkan file {$title}.";
                    $class = "primary";
                }
            @endphp
            <div class="alert alert-{{$class}}">
                <b class="text-center">{{$message}}</b>
            </div>
        @endif
        <div class="card card-primary">
            <div class="card-header">
                <h4>
                    <a href="{{route('arsip.index')}}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i></a>
                </h4>
                <div class="card-header-action">
                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action</a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <li class="dropdown-title">Select</li>
                            <li><a href="javascript:void(0)" class="dropdown-item" id="edit" data-id="{{$data[0]->id}}"><i class="fas fa-pen mr-2 text-warning"></i> Edit</a></li>
                            <li>
                                <form action="{{url('/arsip/delete/' . $data[0]->id)}}" method="POST" id="formDelete">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="dropdown-item"></button>
                                    <a href="javascript:void(0)" id="delete" class="dropdown-item"><i class="fas fa-trash mr-2 text-danger"></i> Hapus</a>
                                </form>
                            </li>
                        </ul>
                    @elseif(Auth::user()->role == 'sekolah')
                        @if (!$now->gt($batas))
                            <button class="btn btn-primary btn-sm" id="save" data-id="{{$data[0]->id}}"><i class="fas fa-file"></i> Kumpulkan File</button>
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-md nowrap" id="table-show" style="width: 100%">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-white text-center">No</th>
                                <th class="text-white text-center">Sekolah</th>
                                <th class="text-white text-center">NPSN</th>
                                <th class="text-white text-center">NSS</th>
                                <th class="text-white text-center">Tanngal</th>
                                <th class="text-white text-center">File</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </section>
@endsection

@push('modal')
<div class="modal fade" id="arsipModal" tabindex="-1" role="dialog" aria-labelledby="arsipModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="arsipModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="novalidate">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="">Judul</label>
                <input type="text" id="title" class="form-control">
                <span class="invalid-feedback-title text-danger"></span>
            </div>
            <div class="form-group">
                <label>Batas Tanggal Pengumpulan</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control daterange-cus">
                    <input type="hidden" id="start_date">
                    <input type="hidden" id="end_date">
                </div>
                <span class="invalid-feedback-start_date text-danger"></span>
            </div>
            <div class="form-group">
                <label for="">Deskripsi</label>
                <textarea class="form-control" id="description" cols="30" rows="10"></textarea>
                <span class="invalid-feedback-description text-danger"></span>
            </div>
            <div id="myProgress">
                <div id="myBar"></div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" id="update"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="saveArsipModal" tabindex="-1" role="dialog" aria-labelledby="saveArsipModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="saveArsipModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="novalidate">
            <input type="hidden" name="arsip_id" id="arsip_id">
            <div class="form-group">
                <label for="">File {{$data[0]->title}}</label>
                <input type="file" name="file" id="file" class="form-control">
                <span class="invalid-feedback-file text-danger"></span>
            </div>
            <div id="myProgressSave">
                <div id="myBarSave"></div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="store"></button>
      </div>
    </div>
  </div>
</div>
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('js/vendor/date-range-picker/date-range-picker.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var currentUrl = window.location.href;

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

            let table = $('#table-show').DataTable({
                serverSide: true,
                ajax: currentUrl,
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'user.username'},
                    {data: 'user.bio.npsn'},
                    {data: 'user.bio.nss'},
                    {data: 'tgl'},
                    {data: 'action'},
                ],
            });

            $('#edit').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: '/arsip/edit/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        $('#arsipModal').modal('show');
                        $('#arsipModalLabel').html(result.title);

                        $('#id').val(result.id);
                        $('#title').val(result.title);
                        $('#start_date').val(result.start_date);
                        $('#end_date').val(result.end_date);
                        $('#description').val(result.description);

                        let startDates = moment(result.start_date);
                        let endDates = moment(result.end_date);

                        $('.daterange-cus').daterangepicker({
                            startDate: startDates,
                            endDate: endDates,
                        }, function (start, end) {
                            var start_date = start.format('YYYY-MM-DD');
                            var end_date = end.format('YYYY-MM-DD');
                            $('#start_date').val(start_date);
                            $('#end_date').val(end_date);
                        });

                        $('#update').html('Update');
                        $('#myProgress').hide();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            $('#update').click(function(e) {
                e.preventDefault();
                let id = $('#id').val();
                let title = $('#title').val();
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();
                let description = $('#description').val();

                $.ajax({
                    url: '/arsip/' + id,
                    type: 'PUT',
                    data: {
                        title: title,
                        start_date: start_date,
                        end_date: end_date,
                        description: description
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result.errors) {
                            $.each(result.errors, function (key, val) {
                                $('#' + key).fadeIn();
                                $('#' + key).addClass('is-invalid');
                                $('.invalid-feedback-' + key).html(val);
                                setTimeout(() => {
                                    $('#' + key).removeClass('is-invalid');
                                    $('.invalid-feedback-' + key).html('');
                                }, 2000);
                            });
                        } else {
                            $('#myProgress').show();
                            var i = 0;
                            if (i == 0) {
                                i = 1;
                                var elem = document.getElementById("myBar");
                                var width = 10;
                                var id = setInterval(frame, 10);
                                function frame() {
                                    if (width >= 100) {
                                        clearInterval(id);
                                        i = 0;
                                    } else {
                                        width++;
                                        elem.style.width = width + "%";
                                        elem.innerHTML = width + "% Updating...";
                                    }
                                }
                            }

                            setTimeout(() => {
                                $('#arsipModal').modal('hide');
                                $('.novalidate')[0].reset();
                                Toast.fire({
                                    icon: 'success',
                                    title: result.success
                                });
                                $('#myProgress').hide();
                                setTimeout(() => {
                                    window.location.href = '/arsip/&title=' + result.data.title + '&arsip=' + result.data.id;
                                }, 2000);
                            }, 2500);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            });

            $('#save').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: '/arsip/edit/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        $('#arsip_id').val(result.id)
                        $('#saveArsipModal').modal('show');
                        $("#saveArsipModalLabel").html('Pengumpulan File');
                        $("#store").html('Kirim');
                        $("#myProgressSave").hide();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            $('#store').click(function(e) {
                e.preventDefault();

                let arsip_id = $('#arsip_id').val();
                let file = $("#file")[0].files[0];

                let formData = new FormData();
                formData.append('arsip_id', arsip_id);
                formData.append('file', file);

                $.ajax({
                    url: '/save/arsip',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if (result.errors) {
                            $.each(result.errors, function (key, val) {
                                $('#' + key).fadeIn();
                                $('#' + key).addClass('is-invalid');
                                $('.invalid-feedback-' + key).html(val);
                                setTimeout(() => {
                                    $('#' + key).removeClass('is-invalid');
                                    $('.invalid-feedback-' + key).html('');
                                }, 2000);
                            });
                        } else {
                            $('.form-group').hide();
                            $("#myProgressSave").show();
                            var i = 0;
                            if (i == 0) {
                                i = 1;
                                var elem = document.getElementById("myBarSave");
                                var width = 10;
                                var id = setInterval(frame, 10);
                                function frame() {
                                    if (width >= 100) {
                                        clearInterval(id);
                                        i = 0;
                                    } else {
                                        width++;
                                        elem.style.width = width + "%";
                                        elem.innerHTML = width + "% Uploading...";
                                    }
                                }
                            }

                            setTimeout(() => {
                                Toast.fire({
                                    icon: 'success',
                                    title: result.message
                                });
                                setTimeout(() => {
                                    $('.form-group').show();
                                    $("#myProgressSave").hide();
                                    $('#file').val('');
                                }, 1000);
                                table.draw();
                            }, 2000);
                            console.log(result);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            $("#delete").click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Warning !',
                    text: "Jika anda menghapus data ini maka seluruh file yang sudah di kumpulkan akan hilang juga.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#formDelete').submit();
                    }
                })
            });
        });
    </script>
@endpush
