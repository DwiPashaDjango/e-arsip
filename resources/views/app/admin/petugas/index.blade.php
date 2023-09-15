@extends('layouts.app')

@section('title', 'Data Petugas')

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
        <div class="card card-primary">
            <div class="card-header">
                <button class="btn btn-primary btn-sm" id="create"><i class="fas fa-plus"></i></button>
            </div>
            <div class="card-body">
                <div class="table-responsive-lg">
                    <table class="table table-bordered table-striped table-md text-center" style="width: 100%" id="table-petugas">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-white text-center">No</th>
                                <th class="text-white text-center">Nama</th>
                                <th class="text-white text-center">Email</th>
                                <th class="text-white text-center">Status</th>
                                <th class="text-white text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </section>
@endsection

@push('modal')
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="msg">
        </div>
        <form class="novalidate">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="">Username</label>
                <input type="text" id="username" class="form-control">
                <span class="invalid-feedback-username text-danger"></span>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" id="email" class="form-control">
                <span class="invalid-feedback-email text-danger"></span>
            </div>
            <div id="hide">
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" id="password" class="form-control">
                    <span class="invalid-feedback-password text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" class="form-control">
                    <span class="invalid-feedback-password_confirmation text-danger"></span>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
@endpush

@push('js')
    <script src="{{asset('js/crud.js')}}"></script>
@endpush
