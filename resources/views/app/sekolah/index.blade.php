@extends('layouts.app')

@section('title')
    Arsip {{Auth::user()->username}}
@endsection

@push('css')

@endpush

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>@yield('title')</h1>
    </div>

    <div class="section-body">
        <div class="card card-primary">
            <div class="card-body">
                <div class="table-responsive-lg">
                    <table class="table table-bordered table-md table-striped text-center" id="arsip">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-white">No</th>
                                <th class="text-white">Nama Arsip</th>
                                <th class="text-white">Tanggal Pengumpulan</th>
                                <th class="text-white">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arsip as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->arsip->title}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->created_at)->translatedFormat('d-F-Y')}}</td>
                                    <td>
                                        <a href="{{asset('storage/arsip/' . $item->file)}}" download="" class="btn btn-info btn-sm"><i class="fas fa-download"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('#arsip').DataTable();
    </script>
@endpush
