<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treservasi extends Model
{
    protected $table = 'treservasi';
    protected $primaryKey = 'id';
    public $timestamps = false; 

    protected $fillable = [
        'pelanggan_id',    
        'tanggalreservasi',
        'waktureservasi',
        'total_preorder',
        'status',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Tpelanggan::class, 'pelanggan_id');
    }

    public function items()
    {
        return $this->hasMany(Treservasi1::class, 'treservasi_id');
    }
}
