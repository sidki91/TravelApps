<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use SoftDeletes;
    protected $table    = 'pembayaran';
    protected $dates    = ['deleted_at'];
    protected $fillable = [
                            'no_pembayaran',
                            'tgl_bayar',
                            'jenis_pembayaran',
                            'nomor_pesanan',
                            'total_tagihan',
                            'jumlah_bayar',
                            'sisa_bayar',
                            'kembali',
                            'status',
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
      
    }
}
