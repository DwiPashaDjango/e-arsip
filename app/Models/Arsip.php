<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;
    protected $table = 'arsips';
    protected $fillable = [
        'title',
        'description',
        'file',
        'start_date',
        'end_date'
    ];

    public function file_arsip()
    {
        return $this->hasMany(FileArsips::class, 'arsip_id', 'id');
    }
}
