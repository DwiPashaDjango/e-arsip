@extends('layouts.app')

@section('title', 'Data Arsip')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('js/vendor/date-range-picker/date-range-picker.min.css')}}" />
    <style>
        .wizard-steps {
            /* display: flex; */
            margin: 0 -10px;
            margin-bottom: 60px;
            counter-reset: wizard-counter;
        }
        .wizard-steps .wizard-step {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 30px;
            text-align: center;
            flex-grow: 1;
            flex-basis: 0;
            margin: 0 10px;
            cursor: pointer;
        }
        .wizard-steps .wizard-step:before {
            counter-increment: wizard-counter;
            content: counter(wizard-counter);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            line-height: 21px;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            background-color: #e3eaef;
        }
        .wizard-steps .wizard-step.wizard-step-active {
            box-shadow: 0 2px 6px #acb5f6;
            background-color: #6777ef;
            color: #fff;
            margin-bottom: 50px;
        }
        .wizard-steps .wizard-step.wizard-step-active:before {
            background-color: #6777ef;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-success {
            background-color: #63ed7a;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-success:before {
            background-color: #63ed7a;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-danger {
            background-color: #fc544b;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-danger:before {
            background-color: #fc544b;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-warning {
            background-color: #ffa426;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-warning:before {
            background-color: #ffa426;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-info {
            background-color: #3abaf4;
            color: #fff;
        }
        .wizard-steps .wizard-step.wizard-step-info:before {
            background-color: #3abaf4;
            color: #fff;
        }
        .wizard-steps .wizard-step .wizard-step-icon .fas, .wizard-steps .wizard-step .wizard-step-icon .far, .wizard-steps .wizard-step .wizard-step-icon .fab, .wizard-steps .wizard-step .wizard-step-icon .fal, .wizard-steps .wizard-step .wizard-step-icon .ion {
            font-size: 34px;
            margin-bottom: 15px;
        }
        .wizard-steps .wizard-step .wizard-step-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .image-not-found {
            width: 30%
        }

        .image-append {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .daterangepicker.dropdown-menu {
            width: auto;
        }
        .daterangepicker .input-mini {
            padding-left: 28px !important;
        }
        .daterangepicker .calendar th, .daterangepicker .calendar td {
            padding: 5px;
            font-size: 12px;
        }

        .ranges li {
            color: #6777ef;
        }
        .ranges li:hover, .ranges li.active {
            background-color: #6777ef;
        }

        .daterangepicker td.active, .daterangepicker td.active:hover {
            background-color: #6777ef;
        }

        .colored-toast.swal2-icon-success {
            background-color: #a5dc86 !important;
        }

        .filter-select {
            padding: 8px;
            margin: 10px;
            width: 88.9%;
            outline: none;
            border-radius: 5px;
            background-color: #fff;
        }

        @media (max-width: 575.98px) {
            .wizard-steps {
                display: block;
            }
            .wizard-steps .wizard-step {
                margin-bottom: 50px;
            }

            .image-not-found {
                width: 100%
            }

            .card .card-header h4 {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>@yield('title')</h1>
        <div class="section-header-breadcrumb">
            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                <button class="btn btn-primary btn-sm" id="create"><i class="fas fa-folder-plus"></i> Buat Arsip Baru</button>
            @endif

            <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle btn-sm ml-2"><i class="fas fa-filter"></i> Filter</a>
            <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                <select class="filter-select" id="years">
                    @php
                        $currentYear = now()->year;
                        $startYear = $currentYear - 5;
                    @endphp
                    <option value="{{$currentYear}}">- Pilih -</option>
                    @for ($year = $currentYear; $year >= $startYear; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </ul>
        </div>
    </div>

    <div class="section-body">
        @if (session()->has('message'))
            <div class="alert alert-success">
                <b>{{session()->get('message')}}</b>
            </div>
        @endif
        <div class="card card-primary">
            <div class="card-header">
                <h4>
                    <nav class="d-inline-block">
                        <ul class="pagination mb-0" id="paginate">
                        </ul>
                    </nav>
                </h4>
                <div class="card-header-action">
                    <form>
                        <input type="text" class="form-control" autocomplete="off" id="search" placeholder="Search">
                        <div class="input-group">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div id="append-2" class="image-append"></div>
                <div class="wizard-steps row" id="append">
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
        <div id="msg">
        </div>
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
        </form>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
@endpush

@push('js')
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="{{asset('js/vendor/date-range-picker/date-range-picker.min.js')}}"></script>
    <script src="{{asset('js/arsip.js')}}"></script>
@endpush
