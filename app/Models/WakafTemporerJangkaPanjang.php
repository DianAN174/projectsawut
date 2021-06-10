<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WakafTemporerJangkaPanjang extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'wakaf_temporer_jangka_panjang';
    protected $primaryKey = 'id';
}
