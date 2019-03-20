<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class KategoriPerjalanan extends Model
{
    use SoftDeletes;
    protected $table    = 'kategori_perjalanan';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_kategori','deskripsi','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }
}
