<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airlines extends Model
{
    use SoftDeletes;
    protected $table    = 'airlines';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_airlines','nama_airlines','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }
}
