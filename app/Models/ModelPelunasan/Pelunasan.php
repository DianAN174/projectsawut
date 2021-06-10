<?php

namespace App\Models\ModelPelunasan;

use App\Models\ModelPengelolaan\KasTabNonbagiHasil;
use App\Models\ModelPenyaluranManfaat\PiutangJangkaPendek;
use App\Models\ModelPenyaluranManfaat\PiutangJangkaPanjang;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelunasan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pelunasan_piutang';
    protected $primaryKey = 'id';

    public function KasTabNonBagiHasil()
    {
        return $this->hasOne(KasTabNonBagiHasil::class);
    }

    public function PiutangJangkaPendek()
    {
        return $this->hasOne(PiutangJangkaPendek::class);
    }

    public function PiutangJangkaPanjang()
    {
        return $this->hasOne(PiutangJangkaPanjang::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($penyaluranBiaya) { // before delete() method call this
            $penyaluranBiaya->KasTabNonBagiHasil()->each(function($KasTabNonBagiHasil) {
                $KasTabNonBagiHasil->delete(); 
            });
            $penyaluranBiaya->PiutangJangkaPendek()->each(function($PiutangJangkaPendek) {
                $PiutangJangkaPendek->delete(); 
            });
            $penyaluranBiaya->PiutangJangkaPanjang()->each(function($PiutangJangkaPanjang) {
                $PiutangJangkaPanjang->delete(); 
            });
        });
    }
}
