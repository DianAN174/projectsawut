<?php

namespace App\Models\Kelayakan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempTable extends Model
{
    use HasFactory;
    protected $table = 'temp_table';
    protected $primaryKey = 'id';
}
