<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kota extends Model
{
    use SoftDeletes;
    protected $table    = 'kota';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_kota','kode_negara','nama_kota','created_by','updated_by'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }

    public function negara()
    {
        return $this->belongsTo('App\Models\Negara','kode_negara','kode_negara')->select(array('kode_negara','nama_negara'));
    }
}
