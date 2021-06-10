<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtangBiaya extends Model
{
    use HasFactory;
    protected $table = 'akun_utang_biaya';
    protected $primaryKey = 'id';
}
