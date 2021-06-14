<?php

namespace App\Models\ModelPengelolaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasDepositoWakaf extends Model
{
    use HasFactory;
    protected $table = 'kas_deposito_wakaf';
    protected $primaryKey = 'id';
}
