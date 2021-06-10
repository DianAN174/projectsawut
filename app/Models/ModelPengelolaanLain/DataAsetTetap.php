<?php

namespace App\Models\ModelPengelolaanLain;
use App\Models\ModelPengelolaanLain\AkunPersediaan;
use App\Models\ModelPengelolaanLain\AkunMesindanKendaraan;
use App\Models\ModelPengelolaanLain\AkunGedungdanBangunandanBangunan;
use App\Models\ModelPengelolaanLain\AkunTanah;
use App\Models\ModelPengelolaanLain\AkunPeralatandanPerlengkapanKantor;
use App\Models\ModelPengelolaanLain\AkunAsetLainLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataAsetTetap extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'data_aset_tetap';
    protected $primaryKey = 'id';

    public function AkunPersediaan()
    {
        return $this->hasOne(AkunPersediaan::class);
    }

    public function AkunMesindanKendaraan()
    {
        return $this->hasOne(AkunMesindanKendaraan::class);
    }

    public function AkunGedungdanBangunan()
    {
        return $this->hasOne(AkunGedungdanBangunan::class);
    }

    public function AkunTanah()
    {
        return $this->hasOne(AkunTanah::class);
    }

    public function AkunPeralatandanPerlengkapanKantor()
    {
        return $this->hasOne(AkunPeralatandanPerlengkapanKantor::class);
    }

    public function AkunAsetLainLain()
    {
        return $this->hasOne(AkunAsetLainLain::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($dataAsetTetap) { 
            $dataAsetTetap->AkunPersediaan()->each(function($AkunPersediaan) {
                $AkunPersediaan->delete(); 
            });
            $dataAsetTetap->AkunMesindanKendaraan()->each(function($AkunMesindanKendaraan) {
                $AkunMesindanKendaraan->delete(); 
            });
            $dataAsetTetap->AkunGedungdanBangunan()->each(function($AkunGedungdanBangunan) {
                $AkunGedungdanBangunan->delete(); 
            });
            $dataAsetTetap->AkunTanah()->each(function($AkunTanah) {
                $AkunTanah->delete(); 
            });
            $dataAsetTetap->AkunPeralatandanPerlengkapanKantor()->each(function($AkunPeralatandanPerlengkapanKantor) {
                $AkunPeralatandanPerlengkapanKantor->delete(); 
            });
            $dataAsetTetap->AkunAsetLainLain()->each(function($AkunAsetLainLain) {
                $AkunAsetLainLain->delete(); 
            });
        });

    }

}