<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PemesananDetail extends Model
{
    use SoftDeletes;
    protected $table    = 'pemesanan_paket_item';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['nomor_pesanan','item','kode_registrasi','kode_paket','harga','created_by','updated_by'];

    public function jamaah()
    {
        return $this->belongsto('App\Models\Formulir','kode_registrasi','kode_registrasi')->select(array('kode_registrasi','nama_lengkap','jk','hp','kabupaten'));
    }
}
