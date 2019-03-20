<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kapasitas extends Model
{
    use SoftDeletes;
    protected $table = 'kapasitas_room';
    protected $dates = ['deleted_at'];
}
