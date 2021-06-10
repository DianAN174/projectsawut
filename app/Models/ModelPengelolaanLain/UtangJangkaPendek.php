<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtangJangkaPendek extends Model
{
    use HasFactory;
    protected $table = 'akun_utang_jangka_pendek';
    protected $primaryKey = 'id';
}
