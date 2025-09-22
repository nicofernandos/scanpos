<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tpelanggan extends Model
{
    
    protected $connection = 'maidatmascgc';
    protected $table = 'tpelanggan';
    protected $primaryKey = 'Id';
    public $incrementing = true;
    protected $KeyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'kodlan',
        'nam',
        'nam1',
        'jenkel',
        'temlah',
        'tgllah',
        'npwp',
        'ema',
        'nowa',
        'ala',
        'prov',
        'kot',
        'cam',
        'lur',
        'pro',
        'kodpos',
    ];
}
