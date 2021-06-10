<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunMesindanKendaraan extends Model
{
    use HasFactory;
    protected $table = 'akun_mesin_dan_kendaraan';
    protected $primaryKey = 'id';
}
