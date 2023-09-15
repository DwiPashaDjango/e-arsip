<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bios;
use App\Models\District;
use App\Models\FileArsips;
use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Charts\UserChart;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'cekBanned']);
    }

    public function index()
    {
        $province = Province::all();
        $count_negeri = Bios::where('status_sekolah', '=', 'Negeri')->count();
        $count_swasta = Bios::where('status_sekolah', '=', 'Swasta')->count();
        $count_arsip  = Arsip::count();
        $count_file   = FileArsips::count();
        $petugas      = User::where('role', 'petugas')->count();
        $sekolah      = User::where('role', 'sekolah')->count();

        $chart = new UserChart;
        $chart->labels(['Negeri', 'Swasta']);
        $chart->dataset('Jumlah Sekolah Negeri & Swasta', 'pie', [$count_negeri, $count_swasta]);

        return view('app.dashboard', [
            'province' => $province,
            'petugas'  => $petugas,
            'sekolah'  => $sekolah,
            'arsip'    => $count_arsip,
            'file'     => $count_file,
            'chart'    => $chart
        ]);
    }

    public function storeBios(Request $request)
    {
        $rules = Validator::make($request->all(), [
            'kepsek' => 'required',
            'npsn' => 'required|unique:bios',
            'nss' => 'required|unique:bios',
            'akreditasi' => 'required',
            'province_id' => 'required',
            'regencie_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'alamat' => 'required',
            'status_sekolah' => 'required',
            'avatars' => 'required|mimes:png,jpeg,jpg',
        ], [
            'kepsek.required' => 'Kepala Sekolah Tidak Boleh Kosong.',
            'npsn.required' => 'Npsn Sekolah Tidak Boleh Kosong.',
            'npsn.unique' => 'Npsn Sekolah Sudah Di Gunakan Sekolah Lain.',
            'nss.required' => 'Nss Sekolah Tidak Boleh Kosong.',
            'nss.unique' => 'Nss Sekolah Telah Di Gunakan Sekolah Lain.',
            'province_id.required' => 'Pilih Provinsi',
            'regencie_id.required' => 'Pilih Kab/Kota',
            'district_id.required' => 'Pilih Kecamatan',
            'village_id.required'  => 'Pilih Desa',
            'alamat.required' => 'Alamat Tidak Boleh Kosong.',
            'avatars.required' => 'Tambahkan Logo Sekolah.',
            'status_sekolah.required' => 'Pilih Status Sekolah Negeri / Swasta'
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        $file = $request->file('avatars');
        $fileName = date('dmy H:i:s') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/avatars/', $fileName);

        $save = Bios::with('user')->create([
            'users_id' => Auth::user()->id,
            'province_id' => $request->province_id,
            'regencie_id' => $request->regencie_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'npsn' => $request->npsn,
            'nss' => $request->nss,
            'kepsek' => $request->kepsek,
            'alamat' => $request->alamat,
            'avatars' => $fileName,
            'status_sekolah' => $request->status_sekolah
        ]);
        return response()->json(['message' => 'Data Sekolah berhasil di lengkapi.']);
    }

    public function getRegencie(Request $request)
    {
        $province_id = $request->province_id;

        $query = Regency::where('province_id', '=', $province_id)->get();
        if (!$query) {
            return response()->json(['message' => 'Not Found Province']);
        }
        return response()->json(['regencie' => $query]);
    }

    public function getDistrict(Request $request)
    {
        $regencie_id = $request->regencie_id;

        $query = District::where('regency_id', '=', $regencie_id)->get();
        if (!$query) {
            return response()->json(['message' => 'Not Found Province']);
        }
        return response()->json(['district' => $query]);
    }

    public function getVillage(Request $request)
    {
        $district_id = $request->district_id;

        $query = Village::where('district_id', '=', $district_id)->get();
        if (!$query) {
            return response()->json(['message' => 'Not Found Province']);
        }
        return response()->json(['village' => $query]);
    }
}
