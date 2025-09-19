<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tsaleorder extends Model
{
    protected $table = 'tsaleorder';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'noso',
        'nopo',
        'tgl',
        'tglpo',
        'kodgud',
        'idlan',
        'idsal',
        'idtop',
        'idcab',
        'iddiv',
        'idpro',
        'ket',
        'tip',
        'ppn',
        'harppn',
        'ppnd',
        'nildpp',
        'disnet',
        'nildisnet',
        'tot',
        'ongkir',
        'iduse',
        'rev',
        'cet',
        'stadon',
        'sta',
        'tglinp',
        'kodlan',
        'trado',
        'kodsal',
        'kodsal1',
        'kodcab',
        'koddiv',
        'kodpro',
        'useid',
    ];

    public function salesorderdetails()
    {
        return $this->hasMany(Tsaleorder1::class, 'idso', 'id');
    }

    public function meja()
    {
        return $this->belongsTo(Tmeja::class,'idmej','id');
    }

}
