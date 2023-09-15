<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordSekolah extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('role', '=', 'sekolah')->get(['id', 'username']);
        return view('auth.reset_sekolah', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json(['data' => $user]);
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
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.required' => 'New password tidak boleh kosong.',
            'password.min' => 'New password minimal 8 huruf atau angka',
            'password.confirmed' => 'New password dan konfirmasi password tidak match.',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.'
        ]);

        if ($rules->fails()) {
            return response()->json(['errors' => $rules->errors()]);
        }

        $save = User::find($id);
        $save->update([
            'password' => Hash::make($request->password)
        ]);
        return response()->json(['message' => 'Behasil mengubah password ' . $save->username]);
    }
}
