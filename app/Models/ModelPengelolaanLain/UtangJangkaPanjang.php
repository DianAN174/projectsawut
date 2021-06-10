<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtangJangkaPanjang extends Model
{
    use HasFactory;
    protected $table = 'akun_utang_jangka_panjang';
    protected $primaryKey = 'id';
}
