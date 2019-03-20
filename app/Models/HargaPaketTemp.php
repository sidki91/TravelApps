<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaPaketTemp extends Model
{
    protected $table    = 'harga_paket_temp';
    protected $fillable = ['token','kode_kapasitas','harga','created_by','updated_by'];
}
