<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaPaket extends Model
{
      use SoftDeletes;
      protected $table    = 'harga_paket';
      protected $dates    = ['deleted_at'];
      protected $fillable = ['kode_harga','kode_paket','kode_kapasitas','harga','created_by','updated_by'];

      public function user()
      {
          return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
      }

      public function kapasitas()
      {
          return $this->belongsto('App\Models\Kapasitas','kode_kapasitas','kode_kapasitas')->select(array('kode_kapasitas','deskripsi'));
      }


}
