<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $table = 'service';
    protected $dates = ['deleted_at'];
    protected $fillable = ['kode_service','tipe_service','keterangan','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }

}
