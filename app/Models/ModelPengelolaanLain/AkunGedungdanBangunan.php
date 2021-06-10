<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunGedungdanBangunan extends Model
{
    use HasFactory;
    protected $table = 'akun_gedung_dan_bangunan';
    protected $primaryKey = 'id';
}
