@extends('layouts.app')

@section('title', 'Reset Password Sekolah')

@push('css')
    <link rel="stylesheet" href="{{asset('js/vendor/select2/select2.min.css')}}">
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

        #search {
            width: 100%;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            box-shadow: none;
        }

        .select2-container .select2-selection--multiple, .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            min-height: 42px;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-select: none;
            outline: none;
            background-color: #fdfdff;
            border-color: #e4e6fc;
        }

        .select2-dropdown {
            border-color: #e4e6fc !important;
        }

        .select2-container.select2-container--open .select2-selection--multiple {
            background-color: #fefeff;
            border-color: #95a0f4;
        }
        .select2-container.select2-container--focus .select2-selection--multiple, .select2-container.select2-container--focus .select2-selection--single {
            background-color: #fefeff;
            border-color: #95a0f4;
        }
        .select2-container.select2-container--open .select2-selection--single {
            background-color: #fefeff;
            border-color: #95a0f4;
        }

        .select2-results__option {
            padding: 10px;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 7px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            min-height: 42px;
            line-height: 42px;
            padding-left: 20px;
            padding-right: 20px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__arrow, .select2-container--default .select2-selection--single .select2-selection__arrow {
            position: absolute;
            top: 1px;
            right: 1px;
            width: 40px;
            min-height: 42px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            color: #fff;
            padding-left: 10px;
            padding-right: 10px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding-left: 10px;
            padding-right: 10px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 5px;
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-results__option[aria-selected=true],
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6777ef;
            color: #fff;
        }

        .select2-results__option {
            padding-right: 10px 15px;
        }
    </style>
@endpush

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>@yield('title')</h1>
    </div>

    <div class="section-body">
        <div class="card card-primary">
            <div class="card-body">
                <form action="" class="form-inline mr-auto">
                    <select class="form-control select2" id="search">
                        <option value="">- Cari Sekolah -</option>
                        @foreach ($user as $item)
                            <option value="{{$item->id}}">{{$item->username}}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        <div id="hide">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 id="username"></h4>
                </div>
                <div class="card-body">
                    <input type="hidden" name="id" id="id">
                    <form action="" class="novalidate">
                        <div class="form-group">
                            <label for="">New Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <span class="invalid-feedback-password text-danger"></span>
                        </div>
                         <div class="form-group">
                            <label for="">Confirmation Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <span class="invalid-feedback-password_confirmation text-danger"></span>
                        </div>
                    </form>

                    <button class="btn btn-primary mt-3 float-right" id="change">Reset Passowrd</button>
                </div>
            </div>
        </div>
    </div>
  </section>
@endsection

@push('js')
<script src="{{asset('js/vendor/select2/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#hide").hide();

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

        $(".select2").change(function(e) {
            e.preventDefault();
            let id = $(this).val();
            if (id != '') {
                $.ajax({
                    url: '/getSekolah/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $("#hide").show();
                        $("#id").val(response.data.id);
                        $("#username").html(response.data.username);
                        $(".novalidate")[0].reset();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            } else {
                $("#id").val('');
                $("#hide").hide();
            }
        });

        $("#change").click(function(e) {
            e.preventDefault();
            let id = $('#id').val();
            let password = $("#password").val()
            let password_confirmation = $("#password_confirmation").val()
            let data = {
                password: password,
                password_confirmation: password_confirmation,
            };

            $.ajax({
                url: '/reset-sekolah/' + id,
                type: 'PUT',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            $("#" + key).addClass('is-invalid');
                            $(".invalid-feedback-" + key).html(value);
                            setTimeout(() => {
                                $("#" + key).removeClass('is-invalid');
                                $(".invalid-feedback-" + key).html('');
                            }, 2000);
                        });
                    } else {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        $("#hide").hide();
                        $(".novalidate")[0].reset();
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        });
    });
</script>
@endpush
