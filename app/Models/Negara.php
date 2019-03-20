<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Negara extends Model
{
    use SoftDeletes;
    protected $table    ='negara';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_negara','nama_negara','created_by','updated_by'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id', 'name'));
    }
}
