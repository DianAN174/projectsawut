<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunTanah extends Model
{
    use HasFactory;
    protected $table = 'akun_tanah';
    protected $primaryKey = 'id';
}
