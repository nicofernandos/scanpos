<?php
namespace App\Models;   
use Illuminate\Database\Eloquent\Model;

class Tmeja extends Model
{
    protected $connection = 'maidatmascgc';
    protected $table = 'tmeja';
    protected $primaryKey ='id';
    public $timestamps = false;
    protected $fillable = [
        'kod',
        'nam',
        'ket',
        'img',
        'sta',
        'rev',
        'createdby',
        'createdat',
        'updatedby',
        'updatedat',
    ];      
}
