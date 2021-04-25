<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanTunaiTemporer extends Model
{
    use HasFactory;
    protected $table = 'penerimaan_wakaf_tunai_temporer';
    protected $primaryKey = 'id';
}
