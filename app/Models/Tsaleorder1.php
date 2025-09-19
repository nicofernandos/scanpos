<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tsaleorder1 extends Model
{
    protected $table = 'tsaleorder1';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'idso',     // foreign key ke tsaleorder
        'nmr',
        'idbrg',
        'nam',
        'idlok',
        'qty',
        'qty1',
        'sat',
        'har',
        'dis',
        'jum',
        'disp',
        'qtyrea',
        'sta',
        'iduse',
        'tglinp',
        'KodBar',
        'UsedId'
    ];

    public function tsaleorder()
    {
        return $this->belongsTo(Tsaleorder::class, 'idso', 'id');
    }


}
