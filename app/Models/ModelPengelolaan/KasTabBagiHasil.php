<?php

namespace App\Models\ModelPengelolaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTabBagiHasil extends Model
{
    use HasFactory;
    protected $table = 'kas_tabungan_bagi_hasil';
    protected $primaryKey = 'id';
}
