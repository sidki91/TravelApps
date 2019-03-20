<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemesanan extends Model
{
    use SoftDeletes;
    protected $table    = 'pemesanan_paket';
    protected $dates    = ['deleted_at'];
    protected $fillable = [
                            'nomor_pesanan',
                            'tgl_pesan',
                            'jam_pesan',
                            'jenis_pemesanan',
                            'kode_kategori',
                            'kode_paket',
                            'bulan_paket',
                            'kode_harga',
                            'harga',
                            'jumlah',
                            'total_harga',
                            'sudah_dibayar',
                            'kembali',
                            'sisa_bayar',
                            'lama_perjalanan',
                            'tgl_berangkat',
                            'tgl_kembali',
                            'berangkat_dari',
                            'status_pembayaran',
                            'keterangan',
                            'created_by',
                            'updated_by'
                          ];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }
    public function bulan()
    {
        return $this->belongsto('App\Models\Bulan','bulan_paket','bulan')->select(array('bulan','bulan_name'));
    }

    public function kategori()
    {
        return $this->belongsto('App\Models\KategoriPerjalanan','kode_kategori','kode_kategori')->select(array('kode_kategori','deskripsi'));
    }

    public function paket()
    {
        return $this->belongsto('App\Models\PaketPerjalanan','kode_paket','kode_paket')->select(array('kode_paket','nama_paket','keterangan'));
    }

    public function sub_paket()
    {
        return $this->belongsto('App\Models\HargaPaket','kode_harga','kode_harga')->select(array('kode_harga','kode_paket','kode_kapasitas'));
    }
}
