<?php

namespace App\Models\ModelPenyaluranManfaat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangJangkaPanjang extends Model
{
    use HasFactory;
    protected $table = 'piutang_jangka_panjang';
    protected $primaryKey = 'id';
}
