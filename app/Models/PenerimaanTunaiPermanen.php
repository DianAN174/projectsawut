<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaanTunaiPermanen extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'penerimaan_wakaf_tunai_permanen';
    protected $primaryKey = 'id';
}
