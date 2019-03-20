<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;
    protected $table    ='hotel';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_hotel','kode_negara','kode_kota','nama_hotel','alamat','telepon','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }

    public function kota()
    {
        return $this->belongsto('App\Models\Kota','kode_kota','kode_kota')->select(array('kode_kota','nama_kota'));
    }
}
