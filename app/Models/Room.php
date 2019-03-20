<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    protected $table    = 'room';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_room','tipe_room','kode_hotel','kode_service','kode_kapasitas','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }

    public function hotel()
    {
        return $this->belongsto('App\Models\Hotel','kode_hotel','kode_hotel')->select(array('kode_hotel','nama_hotel','kode_negara'));
    }

    public function service()
    {
        return $this->belongsto('App\Models\Service','kode_service','kode_service')->select(array('kode_service','tipe_service'));
    }

    public function kapasitas()
    {
        return $this->belongsto('App\Models\Kapasitas','kode_kapasitas','kode_kapasitas')->select(array('kode_kapasitas','deskripsi'));
    }
}
