<?php

namespace App\Models\Kelayakan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    use HasFactory;
    protected $table = 'answers';
    protected $primaryKey = 'id';
}
