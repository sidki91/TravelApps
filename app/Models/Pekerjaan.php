<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pekerjaan extends Model
{
    use SoftDeletes;
    protected $table    = 'pekerjaan';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_pekerjaan','deskripsi','created_by','updated_by'];



    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }
}
