@extends('layouts.app')

@section('title', 'Data Sekolah')


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
                    <table class="table table-bordered table-striped table-md text-center" style="width: 100%" id="table-users">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-center text-white">No</th>
                                <th class="text-center text-white">Nama Sekolah</th>
                                <th class="text-center text-white">Kepala Sekolah</th>
                                <th class="text-center text-white">Email Sekolah</th>
                                <th class="text-center text-white">NPSN</th>
                                <th class="text-center text-white">NSS</th>
                                <th class="text-center text-white">Akreditasi</th>
                                <th class="text-center text-white">Status</th>
                                <th class="text-center text-white">Action</th>
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
                <label for="">Nama Sekolah</label>
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

<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="activate">

        </div>
        <form class="wizard-content mt-2">
            <div class="wizard-pane">
                <div class="tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Nama Sekolah</label>
                                <input type="text" id="username-show" readonly class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Email Sekolah</label>
                                <input type="text" id="email-show" readonly class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Kepala Sekolah</label>
                                <input type="text" id="kepsek" readonly class="form-control">
                                <span class="text-danger invalid-feedback-kepsek"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">NPSN</label>
                                <input type="text" id="npsn" readonly class="form-control">
                                <span class="text-danger invalid-feedback-npsn"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">NSS</label>
                                <input type="text" id="nss" readonly class="form-control">
                                <span class="text-danger invalid-feedback-nss"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Akreditasi</label>
                                <select id="akreditasi" @readonly(true) class="form-control">
                                </select>
                                <span class="text-danger invalid-feedback-akreditasi"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Provinsi</label>
                                <select class="form-control" @readonly(true) id="province_id">
                                    <option value="">- Pilih -</option>
                                </select>
                                <span class="text-danger invalid-feedback-province_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Kab/Kota</label>
                                <select class="form-control" @readonly(true) id="regencie_id">
                                </select>
                                <span class="text-danger invalid-feedback-regencie_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Kecamatan</label>
                                <select class="form-control" @readonly(true) id="district_id">
                                </select>
                                <span class="text-danger invalid-feedback-district_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="text-md-right text-left">Desa</label>
                                <select class="form-control" @readonly(true) id="village_id">
                                </select>
                                <span class="text-danger invalid-feedback-village_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="text-md-right text-left">Alamat Sekolah</label>
                                <textarea id="alamat" readonly class="form-control" cols="30" rows="10"></textarea>
                                <span class="text-danger invalid-feedback-alamat"></span>
                            </div>
                        </div>
                    </div>
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
    <script src="{{asset('js/users.js')}}"></script>
@endpush
