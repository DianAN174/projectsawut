<?php

namespace App\Models\ModelPengelolaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTunai extends Model
{
    use HasFactory;
    protected $table = 'kas_tunai';
    protected $primaryKey = 'id';
}
