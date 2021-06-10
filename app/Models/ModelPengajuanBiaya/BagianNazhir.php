<?php

namespace App\Models\ModelPengajuanBiaya;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianNazhir extends Model
{
    use HasFactory;
    protected $table = 'bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf';
    protected $primaryKey = 'id';
}
