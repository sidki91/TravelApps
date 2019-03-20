<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pendidikan extends Model
{
    use SoftDeletes;
    protected $table    = 'pendidikan';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_pendidikan','deskripsi','created_by','updated_by'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id', 'name'));
    }
}