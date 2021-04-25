<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataWakif extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'data_wakif';
    protected $primaryKey = 'id';

    public function ptp()
    {
        return $this->hasOne(PenerimaanTunaiPermanen::class);
    }

    public function ptt()
    {
        return $this->hasOne(PenerimaanTunaiTemporer::class);
    }

    // this is the recommended way for declaring event handlers
    public static function boot() {
        parent::boot();
        self::deleting(function($dataWakif) { // before delete() method call this
            $dataWakif->ptp()->each(function($ptp) {
                $ptp->delete(); // <-- direct deletion
            });
            $dataWakif->ptt()->each(function($ptt) {
                $ptt->delete(); // <-- raise another deleting event on Post to delete comments
            });
        });
    }
}
