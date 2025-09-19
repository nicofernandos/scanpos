<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'tbarang';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    
    protected $fillable = [
        'tip',
        'kodbar',
        'nam',
        'tipbar',
        'gru',
        'jen',
        'mer',
        'war',
        'stomin',
        'sat',
        'harjua',
        'harbel',
        'idmas',
        'kodtip',
        'idsal',
        'barcode',
        'ket',
        'hap',
        'rev',
        'iduse',
        'ad',
        'tglinp',
        'kodmas',
        'kodsal',
        'useid'
    ];

    




}
