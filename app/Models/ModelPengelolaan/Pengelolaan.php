<?php

namespace App\Models\ModelPengelolaan;

use App\Models\ModelPengelolaan\KasTunai;
use App\Models\ModelPengelolaan\KasTabWakaf;
use App\Models\ModelPengelolaan\KasTabBagiHasil;
use App\Models\ModelPengelolaan\KasTabNonbagiHasil;
use App\Models\ModelPengelolaan\KasDepositoWakaf;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengelolaan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pengelolaan_wakaf';
    protected $primaryKey = 'id';

    public function KasTunai()
    {
        return $this->hasOne(KasTunai::class);
    }

    public function KasTabunganWakaf()
    {
        return $this->hasOne(KasTabunganWakaf::class);
    }

    public function KasTabunganBagiHasil()
    {
        return $this->hasOne(KasTabunganBagiHasil::class);
    }

    public function KasTabunganNonBagiHasil()
    {
        return $this->hasOne(KasTabunganNonBagiHasil::class);
    }

    public function KasDepositoWakaf()
    {
        return $this->hasOne(KasDepositoWakaf::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($pengelolaan) { // before delete() method call this
            $pengelolaan->KasTunai()->each(function($KasTunai) {
                $KasTunai->delete(); // <-- direct deletion
            });
            $pengelolaan->KasTabunganWakaf()->each(function($KasTabunganWakaf) {
                $KasTabunganWakaf->delete(); // <-- raise another deleting event on Post to delete comments
            });
            $pengelolaan->KasTabunganBagiHasil()->each(function($KasTabunganBagiHasil) {
                $KasTabunganBagiHasil->delete(); 
            });
            $pengelolaan->KasTabunganNonBagiHasil()->each(function($KasTabunganNonBagiHasil) {
                $KasTabunganNonBagiHasil->delete(); 
            });
            $pengelolaan->KasDepositoWakaf()->each(function($KasDepositoWakaf) {
                $KasDepositoWakaf->delete(); 
            });
        });
    }
}
