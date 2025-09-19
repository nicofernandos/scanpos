<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualandetail extends Model
{
    protected $table = 'tjual1';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'idj',
        'nom',
        'idbar',
        'nam',
        'qty',
        'qty1',
        'sat',
        'har',
        'dis',
        'subtot',
        'idlok',
        'stapos',
        'qtyrea',
        'disp',
        'nopac',
        'idso',
        'nmr1',
        'sta',
        'iduse',
        'tglinp',
        'noso',
        'kodbar',       
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id', 'idj');
    }
}
