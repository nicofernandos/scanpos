<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treservasi extends Model
{
    protected $table = 'treservasi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'nohp',
        'tanggalreservasi',
        'waktureservasi'
    ];
}