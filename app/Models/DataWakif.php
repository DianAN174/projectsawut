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
    //wtpd = wakaf temporer jangka pendek
    public function wtpd()
    {
        return $this->hasOne(WakafTemporerJangkaPendek::class);
    }

    //wtpj = wakaf temporer jangka panjang
    public function wtpj()
    {
        return $this->hasOne(WakafTemporerJangkaPanjang::class);
    }


    // this is the recommended way for declaring event handlers
    public static function boot() {
        parent::boot();
        self::deleting(function($dataWakif) { // before delete() method call this
            $dataWakif->ptp()->each(function($ptp) {
                $ptp->delete(); // <-- direct deletion
            });
            $dataWakif->wtpd()->each(function($wtpd) {
                $wtpd->delete(); // <-- raise another deleting event on Post to delete comments
            });
            $dataWakif->wtpj()->each(function($wtpj) {
                $wtpj->delete(); // <-- raise another deleting event on Post to delete comments
            });
        });
    }
}
