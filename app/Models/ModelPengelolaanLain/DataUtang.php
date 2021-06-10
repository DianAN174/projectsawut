<?php

namespace App\Models\ModelPengelolaanLain;

use App\Models\ModelPengelolaanLain\UtangBiaya;
use App\Models\ModelPengelolaanLain\UtangJangkaPanjang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DataUtang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'data_utang';
    protected $primaryKey = 'id';

    public function UtangBiaya()
    {
        return $this->hasOne(UtangBiaya::class);
    }

    public function UtangJangkaPanjang()
    {
        return $this->hasOne(UtangJangkaPanjang::class);
    }


    public static function boot() {
        parent::boot();
        self::deleting(function($dataUtang) { 
            $dataUtang->UtangBiaya()->each(function($UtangBiaya) {
                $UtangBiaya->delete(); 
            });
            $dataUtang->UtangJangkaPanjang()->each(function($UtangJangkaPanjang) {
                $UtangJangkaPanjang->delete(); 
            });
        });
    }
}

