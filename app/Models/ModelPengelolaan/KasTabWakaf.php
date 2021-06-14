<?php

namespace App\Models\ModelPengelolaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTabWakaf extends Model
{
    use HasFactory;
    protected $table = 'kas_tabungan_wakaf';
    protected $primaryKey = 'id';
}
