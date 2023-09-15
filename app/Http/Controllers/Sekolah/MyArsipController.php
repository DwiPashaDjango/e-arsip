<?php

namespace App\Http\Controllers\Sekolah;

use App\Http\Controllers\Controller;
use App\Models\FileArsips;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyArsipController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth', 'cekBanned']);
    }

    public function index()
    {
        $arsip = FileArsips::with('arsip')->where('users_id', '=', Auth::user()->id)->get();
        return view('app.sekolah.index', compact('arsip'));
    }
}
