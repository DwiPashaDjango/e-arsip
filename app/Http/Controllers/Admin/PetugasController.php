<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PetugasController extends Controller
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
            $data = User::where('role', '=', 'petugas')->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $status = '<span class="badge bg-primary text-white">Aktif</>';
                    } else {
                        $status = '<span class="badge bg-danger text-white">Nonaktif</>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    if ($row->status == 1) {
                        $btn = '<a href="#" data-toggle="dropdown" class="btn btn-outline-primary btn-sm dropdown-toggle">Pilih</a>
                                <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    <li class="dropdown-title">Pilih Aksi</li>
                                    <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="banned"><i class="fas fa-ban mr-2 text-info"></i> Banned</a></li>
                                    <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="edit"> <i class="fas fa-pen mr-2 text-warning"></i> Edit</a></li>
                                    <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="delete"><i class="fas fa-trash mr-2 text-danger"></i> Hapus</a></li>
                                </ul>';
                    } else {
                        $btn = '<a href="#" data-toggle="dropdown" class="btn btn-outline-primary btn-sm dropdown-toggle">Pilih</a>
                                <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    <li class="dropdown-title">Pilih Aksi</li>
                                    <li><a href="#" class="dropdown-item" data-id="' . $row->id . '" id="unbanned"><i class="fas fa-check mr-2 text-success"></i> Akifkan</a></li>
                                </ul>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('app.admin.petugas.index');
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

        $save = User::create([
            'username' => $request->username,
            'role' => 'petugas',
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => 'Berhasil menambahkan petugas baru.']);
    }

    /**
     * Banned the user specified resource.
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
        return response()->json(['success' => 'Berhasil menonaktfkan ' . $data->username, 'data' => $data]);
    }

    /**
     * Unbanned the user specified resource.
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
        return response()->json(['success' => 'Berhasil mengaktifkan ' . $data->username, 'data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return response()->json($data);
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
        return response()->json(['success' => 'Berhasil memperbarui data ' . $data->username]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::find($id);
        $data->bio()->delete();
        $data->delete();
        return response()->json(['success' => 'Berhasil menghapus petugas ' . $data->username]);
    }
}
