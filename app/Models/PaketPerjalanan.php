<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketPerjalanan extends Model
{
    use SoftDeletes;
    protected $table    = 'paket_perjalanan';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_paket',
                           'bulan',
                           'kode_kategori',
                           'nama_paket',
                           'tujuan_kota',
                           'lama_perjalanan',
                           'kegiatan',
                           'harga',
                           'keterangan',
                           'created_by',
                           'updated_by'];


    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }

    public function kategori_paket()
    {
        return $this->belongsto('App\Models\KategoriPerjalanan','kode_kategori','kode_kategori')->select(array('kode_kategori','deskripsi'));
    }

    public function kota()
    {
        return $this->belongsto('App\Models\Kota','tujuan_kota','kode_kota')->select(array('kode_kota','nama_kota'));
    }

    public function get_bulan()
    {
        return $this->belongsto('App\Models\Bulan','bulan','bulan')->select(array('bulan','bulan_name'));
    }
}
