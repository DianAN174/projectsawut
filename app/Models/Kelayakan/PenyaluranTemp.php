<?php

namespace App\Models\Kelayakan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyaluranTemp extends Model
{
    use HasFactory;
    protected $table = 'penyaluran_temp';
    protected $primaryKey = 'id';
}
