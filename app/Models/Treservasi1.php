<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treservasi1 extends Model
{
    protected $table = 'treservasi1'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'treservasi_id',
        'barang_id',
        'nama_barang',
        'satuan',
        'harga_satuan',
        'qty',
        'subtotal',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Treservasi::class, 'treservasi_id');
    }
}
