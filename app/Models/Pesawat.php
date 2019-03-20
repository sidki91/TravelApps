<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesawat extends Model
{
    use SoftDeletes;
    protected $table    = 'pesawat';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_pesawat','kode_airlines','nama_pesawat','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }

    public function airlines()
    {
        return $this->belongsto('App\Models\Airlines','kode_airlines','kode_airlines')->select(array('kode_airlines','nama_airlines'));
    }
}
