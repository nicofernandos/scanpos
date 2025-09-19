<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'tjual';
    protected $primaryKey = 'id';
    public $timestamps = false; 

    protected $fillable = [
        'nofp',
        'tglfp',
        'wakfp',
        'totnet',
        'nohp',
    ];

    public function penjualanDetails(){
        return $this->hasMany(Penjualandetail::class, 'idj', 'id');
    }


}
