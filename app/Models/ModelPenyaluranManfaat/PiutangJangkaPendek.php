<?php

namespace App\Models\ModelPenyaluranManfaat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangJangkaPendek extends Model
{
    use HasFactory;
    protected $table = 'piutang_jangka_pendek';
    protected $primaryKey = 'id';
}
