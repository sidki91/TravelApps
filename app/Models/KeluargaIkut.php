<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeluargaIkut extends Model
{
    use SoftDeletes;
    protected $table    = 'keluarga_ikut';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_registrasi','item','nama','kode_hubungan','tlp','created_by','updated_by'];

    public function get_hubungan()
    {
        return $this->belongsTo('App\Models\Hubungan','kode_hubungan','kode_hubungan')->select(array('kode_hubungan','deskripsi'));
    }
}
