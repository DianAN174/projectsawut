<?php

namespace App\Models\ModelPengelolaanLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAkumulasi extends Model
{
    use HasFactory;
    protected $table = 'data_akumulasi';
    protected $primaryKey = 'id';
}
