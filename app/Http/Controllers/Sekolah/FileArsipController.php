<?php

namespace App\Http\Controllers\Sekolah;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\FileArsips;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileArsipController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'cekBanned']);
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
            'file' => 'required|mimes:pdf,xlsx,docx|max:2048'
        ], [
            'file.required' => 'Pilih salah satu field untuk di upload.',
            'file.mimes' => 'File hanya boleh pdf,xlxs,docx'
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        $find = Arsip::find($request->arsip_id);
        $titleWithoutSpace = str_replace(' ', '', $find->title);

        $file = $request->file('file');
        $fileName =  $titleWithoutSpace . '/' . date('dmy H:i:s') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/arsip/', $fileName);

        $save = FileArsips::create([
            'arsip_id' => $request->arsip_id,
            'users_id' => Auth::user()->id,
            'tgl' => Carbon::now(),
            'file' => $fileName
        ]);
        return response()->json(['message' => 'Berhasil mengumpulkan.', 'data' => $save]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
