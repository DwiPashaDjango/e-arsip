<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\FileArsips;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Nullix\CryptoJsAes\CryptoJsAes;
use Yajra\DataTables\Facades\DataTables;

class ArsipController extends Controller
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
        return view('app.petugas.arsip.index');
    }

    /**
     * Json the arsip resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getJson()
    {
        $role = Auth::user()->role;
        $arsip = Arsip::orderBy('id', 'desc')->paginate(16);
        return response()->json(['arsip' => $arsip, 'role' => $role]);
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
            'title' => 'required',
            'start_date' => 'required|date',
            'description' => 'required'
        ], [
            'title.required' => 'Judul arsip tidak boleh kosong.',
            'start_date.required' => 'Batas tanggal pengumpulan tidak boleh kosong.',
            'description.required' => 'Deskripsi arsip tidak boleh kosong.'
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        $save = Arsip::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description
        ]);
        return response()->json(['success' => 'Berhasil membuat arsip ' . $save->title]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($title, $id)
    {
        $data = Arsip::with('file_arsip')->where(['title' => $title], ['id' => $id])->get();
        if (request()->ajax()) {
            $file = FileArsips::with('user', 'user.bio')->where('arsip_id', $id)->orderBy('id', 'desc')->get();
            return DataTables::of($file)
                ->addColumn('tgl', function ($row) {
                    $tgl = Carbon::parse($row->tgl)->translatedFormat('d F Y');
                    return $tgl;
                })
                ->addColumn('action', function ($row) {
                    $asset = asset('storage/arsip/' . $row->file);
                    $btn = '<a href="' . $asset . '" class="btn btn-info btn-sm" download=""><i class="fas fa-download"></i>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('app.petugas.arsip.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Arsip::find($id);
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
            'title' => 'required',
            'start_date' => 'required|date',
            'description' => 'required'
        ], [
            'title.required' => 'Judul arsip tidak boleh kosong.',
            'start_date.required' => 'Batas tanggal pengumpulan tidak boleh kosong.',
            'description.required' => 'Deskripsi arsip tidak boleh kosong.'
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        $data = Arsip::find($id);
        $data->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description
        ]);
        return response()->json(['success' => 'Berhasil mengubah arsip ' . $data->title, 'data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Arsip::with(['file_arsip'])->find($id);
        if ($data) {
            foreach ($data->file_arsip as $value) {
                $path = public_path('storage/arsip/' . $value->file);
                if (file_exists($path)) {
                    unlink($path);
                }
                $value->delete();
            }
            $data->delete();
            return redirect()->route('arsip.index')->with(['message' => 'Berhasil menghapus arsip.']);
        }
        return back()->with(['error' => 'Data arsip tidak di temukan.']);
    }

    public function search(Request $request)
    {
        $role = Auth::user()->role;
        $search = $request->search;
        $query = Arsip::where('title', 'like', '%' . $search . '%')
            ->orWhere('created_at', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json(['data' => $query, 'role' => $role]);
    }

    public function filterByYear(Request $request)
    {
        $role = Auth::user()->role;
        $years = $request->input('years');
        $query = Arsip::whereYear('created_at', $years)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json(['data' => $query, 'role' => $role]);
    }
}
