<?php

namespace App\Models\ModelPengajuanBiaya;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BebanPengelolaandanPengembangan extends Model
{
    use HasFactory;
    protected $table = 'beban_pengelolaan_dan_pengembangan_wakaf';
    protected $primaryKey = 'id';
}
