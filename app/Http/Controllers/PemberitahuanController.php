<?php

namespace App\Http\Controllers;

use App\Models\Pemberitahuans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PemberitahuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getJsonPemberitahuan()
    {
        $role = Auth::user()->role;
        $users_id = Auth::user()->id;
        $data = Pemberitahuans::with('user')->orderBy('id', 'desc')->get();
        foreach ($data as $value) {
            $value->tanggal = Carbon::parse($value->created_at)->translatedFormat('d, F Y');
        }
        return response()->json(['data' => $data, 'role' => $role, 'users_id' => $users_id]);
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
            'description' => 'required'
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        $data = Pemberitahuans::create([
            'users_id' => Auth::user()->id,
            'description' => $request->description
        ]);
        return response()->json(['data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Pemberitahuans::find($id);
        $data->delete();
        return response()->json(['message' => 'Successfully deleted message']);
    }
}
