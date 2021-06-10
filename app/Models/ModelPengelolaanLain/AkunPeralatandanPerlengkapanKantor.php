<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunPeralatandanPerlengkapanKantor extends Model
{
    use HasFactory;
    protected $table = 'akun_peralatan_dan_perlengkapan_kantor';
    protected $primaryKey = 'id';
}
