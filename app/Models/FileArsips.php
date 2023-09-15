<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileArsips extends Model
{
    use HasFactory;
    protected $table = 'file_arsips';
    protected $fillable = [
        'arsip_id',
        'users_id',
        'tgl',
        'file'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id', 'id');
    }
}
