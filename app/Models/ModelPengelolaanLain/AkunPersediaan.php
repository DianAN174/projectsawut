<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunPersediaan extends Model
{
    use HasFactory;
    protected $table = 'akun_persediaan';
    protected $primaryKey = 'id';
}
