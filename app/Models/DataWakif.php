<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataWakif extends Model
{
    use HasFactory;
    protected $table = 'data_wakif';
    protected $primaryKey = 'id';
}
