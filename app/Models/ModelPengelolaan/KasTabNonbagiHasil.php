<?php

namespace App\Models\ModelPengelolaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTabNonbagiHasil extends Model
{
    use HasFactory;
    protected $table = 'kas_tabungan_non_bagi_hasil';
    protected $primaryKey = 'id';
}
