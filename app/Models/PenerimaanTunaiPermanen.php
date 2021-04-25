<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanTunaiPermanen extends Model
{
    use HasFactory;
    protected $table = 'penerimaan_wakaf_tunai_permanen';
    protected $primaryKey = 'id';
}
