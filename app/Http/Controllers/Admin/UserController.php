<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'cekBanned']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = User::with('bio')->where('role', '=', 'sekolah')->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('kepsek', function ($row) {
                    if ($row->bio != null) {
                        $kepsek = $row->bio->kepsek;
                    } else {
                        $kepsek = '<span class="badge bg-warning text-white">Belum Mengisi</span>';
                    }
                    return $kepsek;
                })
                ->addColumn('npsn', function ($row) {
                    if ($row->bio != null) {
                        $npsn = $row->bio->npsn;
                    } else {
                        $npsn = '<span class="badge bg-warning text-white">Belum Mengisi</span>';
                    }
                    return $npsn;
                })
                ->addColumn('nss', function ($row) {
                    if ($row->bio != null) {
                        $nss = $row->bio->nss;
                    } else {
                        $nss = '<span class="badge bg-warning text-white">Belum Mengisi</span>';
                    }
                    return $nss;
                })
                ->addColumn('akreditasi', function ($row) {
                    if ($row->bio != null) {
                        $akreditasi = $row->bio->akreditasi;
                    } else {
                        $akreditasi = '<span class="badge bg-warning text-white">Belum Mengisi</span>';
                    }
                    return $akreditasi;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $status = '<span class="badge bg-success text-white">Aktif</span>';
                    } else {
                        $status = '<span class="badge bg-danger text-white">Nonaktif</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    if ($row->status == 1) {
                        if (Auth::user()->role == "petugas") {
                            $btn = '<a href="#" data-toggle="dropdown" class="btn btn-outline-primary btn-sm dropdown-toggle">Pilih</a>
                                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <li class="dropdown-title">Pilih Aksi</li>
                                            <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="show"><i class="fas fa-eye mr-2 text-info"></i> Show</a></li>
                                        </ul>';
                        } else {
                            $btn = '<a href="#" data-toggle="dropdown" class="btn btn-outline-primary btn-sm dropdown-toggle">Pilih</a>
                                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <li class="dropdown-title">Pilih Aksi</li>
                                            <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" data-username="' . $row->username . '" id="banned"><i class="fas fa-ban mr-2 text-info"></i> Banned</a></li>
                                            <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="show"><i class="fas fa-eye mr-2 text-primary"></i> Show</a></li>
                                            <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="edit"> <i class="fas fa-pen mr-2 text-warning"></i> Edit</a></li>
                                            <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" data-username="' . $row->username . '" id="delete"><i class="fas fa-trash mr-2 text-danger"></i> Hapus</a></li>
                                        </ul>';
                        }
                    } else {
                        if (Auth::user()->role == "petugas") {
                            $btn = '<a href="#" data-toggle="dropdown" class="btn btn-outline-primary btn-sm dropdown-toggle">Pilih</a>
                                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <li class="dropdown-title">Pilih Aksi</li>
                                            <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="show"><i class="fas fa-eye mr-2 text-info"></i> Show</a></li>
                                        </ul>';
                        } else {
                            $btn = '<a href="#" data-toggle="dropdown" class="btn btn-outline-primary btn-sm dropdown-toggle">Pilih</a>
                                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <li class="dropdown-title">Pilih Aksi</li>
                                            <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="unbanned"><i class="fas fa-check mr-2 text-success"></i> Akifkan</a></li>
                                        </ul>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status', 'npsn', 'nss', 'akreditasi', 'kepsek'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('app.admin.sekolah.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        User::create([
            'username' => $request->username,
            'role' => 'sekolah',
            'email' => $request->email,
            'status' => 0,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(['message' => 'Berhasil menambahkan data sekolah baru.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::with('bio', 'bio.province', 'bio.regencie', 'bio.district', 'bio.village')->find($id);
        return response()->json(['data' => $data]);
    }

    /**
     * Edit the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        $data = User::find($id);
        $data->update([
            'username' => $request->username,
            'email' => $request->email
        ]);
        return response()->json(['message' => 'Berhasil memperbarui data ' . $data->username]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::with('bio', 'file_arsip_user')->find($id);

        if ($data) {
            $pathImage = public_path('storage/avatars/' . $data->bio->avatars);
            foreach ($data->file_arsip_user as $value) {
                $pathFileArsip = public_path('storage/arsip/' . $value->file);
                if (file_exists($pathFileArsip)) {
                    unlink($pathFileArsip);
                }
                $value->delete();
            }
            unlink($pathImage);
            $data->bio()->delete();
            $data->delete();
            return response()->json(['message' => 'Berhasil menghapus data sekolah.']);
        }
        return response()->json(['message' => 'Data tidak di temukan']);
    }

    /**
     * Banned the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function banned($id)
    {
        $data = User::find($id);
        $data->update([
            'status' => 0
        ]);
        return response()->json(['message' => 'Berhasil menonaktifkan akun' . $data->username]);
    }

    /**
     * Unbanned the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unbanned($id)
    {
        $data = User::find($id);
        $data->update([
            'status' => 1
        ]);
        return response()->json(['message' => 'Berhasil mengaktifkan ' . $data->username]);
    }
}
