<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formulir extends Model
{
    use SoftDeletes;
    protected $table    = 'formulir';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['kode_registrasi',
                           'tgl_registrasi',
                           'nama_lengkap',
                           'nama_ayah_kandung',
                           'tempat_lahir',
                           'tgl_lahir',
                           'umur',
                           'gol_darah',
                           'jk',
                           'pendidikan',
                           'status',
                           'tgl_pernikahan',
                           'pekerjaan',
                           'nama_instansi',
                           'alamat_instansi',
                           'telepon_instansi',
                           'nomor_pasport',
                           'tgl_dikeluarkan',
                           'tempat_dikeluarkan',
                           'masa_berlaku',
                           'alamat',
                           'rt',
                           'rw',
                           'nomor',
                           'provinsi',
                           'kabupaten',
                           'kecamatan',
                           'kelurahan',
                           'kode_pos',
                           'telepon',
                           'hp',
                           'email',
                           'kategori_perjalanan',
                           'kode_paket',
                           'kode_harga',
                           'harga',
                           'program',
                           'berangkat_dari',
                           'penyakit_derita',
                           'pengalaman_haji',
                           'jumlah_haji',
                           'terakhir_tahun_haji',
                           'pengalaman_umroh',
                           'jumlah_umroh',
                           'terakhir_tahun_umroh',
                           'created_by',
                           'updated_by'];


    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }

    public function get_provinsi()
    {
        return $this->belongsTo('App\Models\Provinsi','provinsi','id_prov')->select(array('id_prov','nama'));
    }

    public function kabupaten_kota()
    {
        return $this->belongsTo('App\Models\Kabupaten','kabupaten','id_kab')->select(array('id_kab','nama'));
    }

    public function get_kecamatan()
    {
        return $this->belongsTo('App\Models\Kecamatan','kecamatan','id_kec')->select(array('id_kec','nama'));
    }

    public function get_kelurahan()
    {
        return $this->belongsTo('App\Models\Kelurahan','kelurahan','id_kel')->select(array('id_kel','nama'));
    }

    public function get_pendidikan()
    {
        return $this->belongsTo('App\Models\Pendidikan','pendidikan','kode_pendidikan')->select(array('kode_pendidikan','deskripsi'));
    }

    public function get_pekerjaan()
    {
        return $this->belongsTo('App\Models\Pekerjaan','pekerjaan','kode_pekerjaan')->select(array('kode_pekerjaan','deskripsi'));
    }

    public function get_kategori_perjalanan()
    {
        return $this->belongsto('App\Models\KategoriPerjalanan','kategori_perjalanan','kode_kategori')->select(array('kode_kategori','deskripsi'));
    }
    public function get_paket_perjalanan()
    {
        return $this->belongsTo('App\Models\PaketPerjalanan','kode_paket','kode_paket')->select(array('kode_paket','nama_paket'));

    }

    

}
