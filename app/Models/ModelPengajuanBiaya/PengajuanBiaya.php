<?php

namespace App\Models\ModelPengajuanBiaya;

use App\Models\ModelPengelolaan\KasTunai;
use App\Models\ModelPengelolaan\KasTabBagiHasil;
use App\Models\ModelPengelolaan\KasTabNonbagiHasil;
use App\Models\ModelPengajuanBiaya\BebanPengelolaandanPengembangan;
use App\Models\ModelPengajuanBiaya\BagianNazhir;
use App\Models\ModelPengajuanBiaya\PentasyarufanManfaat;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBiaya extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pengajuan_biaya';
    protected $primaryKey = 'id';

    public function KasTunai()
    {
        return $this->hasOne(KasTunai::class);
    }

    public function KasTabBagiHasil()
    {
        return $this->hasOne(KasTabBagiHasil::class);
    }

    public function KasTabNonBagiHasil()
    {
        return $this->hasOne(KasTabNonBagiHasil::class);
    }

    //BPP = beban pengelolaan dan pengembangan
    public function BPP()
    {
        return $this->hasOne(BebanPengelolaandanPengembangan::class);
    }

    public function BagianNazhir()
    {
        return $this->hasOne(BagianNazhir::class);
    }

    public function PentasyarufanManfaat()
    {
        return $this->hasOne(PentasyarufanManfaat::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($pengajuan) { // before delete() method call this
            $pengajuan->KasTunai()->each(function($KasTunai) {
                $KasTunai->delete(); // <-- direct deletion
            });
            $pengajuan->KasTabBagiHasil()->each(function($KasTabBagiHasil) {
                $KasTabBagiHasil->delete(); 
            });
            $pengajuan->KasTabNonBagiHasil()->each(function($KasTabNonBagiHasil) {
                $KasTabNonBagiHasil->delete();
            });
            $pengajuan->BPP()->each(function($BPP) {
                $BPP->delete(); // <-- raise another deleting event on Post to delete comments
            });
            $pengajuan->BagianNazhir()->each(function($BagianNazhir) {
                $BagianNazhir->delete(); 
            });
            $pengajuan->PentasyarufanManfaat()->each(function($PentasyarufanManfaat) {
                $PentasyarufanManfaat->delete(); 
            });
        });
    }
}
