<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeluargaHubungi extends Model
{
    use SoftDeletes;
    protected $table    = 'keluarga_dihubungi';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_registrasi','item','nama','alamat','telp','created_by','updated_by'];
}
