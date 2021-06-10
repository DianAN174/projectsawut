<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\DataWakif;
use App\Models\PenerimaanTunaiPermanen;
use App\Models\WakafTemporerJangkaPendek;
use App\Models\WakafTemporerJangkaPanjang;

use App\Models\ModelPengajuanBiaya\PengajuanBiaya;
use App\Models\ModelPengajuanBiaya\BebanPengelolaandanPengembangan;
use App\Models\ModelPengajuanBiaya\BagianNazhir;
use App\Models\ModelPengajuanBiaya\PentasyarufanManfaat;

use App\Models\ModelPengelolaan\KasTunai;
use App\Models\ModelPengelolaan\KasTabBagiHasil;
use App\Models\ModelPengelolaan\KasTabNonBagiHasil;
use App\Models\ModelPengelolaan\KasDepositoWakaf;

use App\Models\ModelPenyaluranManfaat\PiutangJangkaPendek;
use App\Models\ModelPenyaluranManfaat\PiutangJangkaPanjang;

use App\Models\ModelPengelolaanLain\UtangBiaya;
use App\Models\ModelPengelolaanLain\UtangJangkaPendek;
use App\Models\ModelPengelolaanLain\UtangJangkaPanjang;

use App\Models\ModelPengelolaanLain\DataAsetTetap;
use App\Models\ModelPengelolaanLain\DataUtang;
use App\Models\ModelPengelolaanLain\AkunDataUtang;
use App\Models\ModelPengelolaanLain\AkunPersediaan;
use App\Models\ModelPengelolaanLain\AkunMesindanKendaraan;
use App\Models\ModelPengelolaanLain\AkunGedungdanBangunandanBangunandanBangunan;
use App\Models\ModelPengelolaanLain\AkunTanah;
use App\Models\ModelPengelolaanLain\AkunPeralatandanPerlengkapanKantor;
use App\Models\ModelPengelolaanLain\AkunAsetLainLain;
use App\Models\ModelPengelolaanLain\DataAkumulasi;

use App\Models\User;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class LaporanRincianAsetWakaf
{
    //GET YEAR
    $year = Carbon::now()->format('Y');

    public function LaporanRincianAsetWakafx2(Request $request)
    {
        //KAS DAN SETARA KAS
            //WAKIF
            //tunai
            $sumKasTunaiWakif = KasTunai::whereYear('created_at', '=', $year)->where('data_wakif_id','!=','0')->sum('saldo');
            $sumKreditTunaiWakif = KasTunai::whereYear('created_at', '=', $year)->where('data_wakif_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhirKasTunaiWakif =$sumKasTunaiWakif - $sumKreditTunaiWakif;

            //HASIL PENGELOLAAN
            //tunai
            $sumKasTunai = KasTunai::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKreditTunai = KasTunai::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhirKasTunai=$sumKasTunai - $sumKreditTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumKredit_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumKredit_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumKredit_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumKredit_kdw;

            $jumlah_kas = $saldoTerakhirKasTunaiWakif + $saldoTerakhirKasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;

        //PIUTANG
        //HASIL PENGELOLAAN
            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumKredit_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumKredit_pja;

        $jumlah_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

        //ASET TETAP
            //TANAH
            $sum_tanah = AkunTanah::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_tanah = AkunTanah::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumKredit_tanah;

            //BANGUNAN
            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumKredit_gedung;

            //KENDARAAN
            $kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumKredit_kendaraan;

            //LAINNYA
            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumKredit_aset_lain - $sumKredit_aset_lain;

            $saldo_terakhir_aset_tetap =  $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_aset_lain;

    //Jumlah aset

           $jumlah_aset = $saldo_terakhir_kas + $saldo_terakhir_piutang + $saldo_terakhir_aset_tetap;

    $array = [$saldo_terakhir_kas, $saldo_terakhir_piutang, $saldo_terakhir_aset_tetap, $jumlah_aset];
        
        /* if ($request->has('export')) {
            if ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('customers.index-pdf', compact('data'));
                return $pdf->download('customer-list.pdf');
            }
        } */

        
    return Response::HttpResponse(200, $array, "Success", false);
    }
        
    public function LaporanRincianAsetWakafx1(Request $request)
    {
        //KAS DAN SETARA KAS
            //WAKIF
            //tunai
            $sumKasTunaiWakif = KasTunai::whereYear('created_at', '=', ($year-1)))->where('data_wakif_id','!=','0')->sum('saldo');
            $sumKreditTunaiWakif = KasTunai::whereYear('created_at', '=', ($year-1)))->where('data_wakif_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhirKasTunaiWakif =$sumKasTunaiWakif - $sumKreditTunaiWakif;

            //HASIL PENGELOLAAN
            //tunai
            $sumKasTunai = KasTunai::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKreditTunai = KasTunai::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhirKasTunai=$sumKasTunai - $sumKreditTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_ktw = KasTabWakaf::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumKredit_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_ktbh = KasTabBagiHasil::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumKredit_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumKredit_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->sum('saldo');
            $sumKredit_kdw = KasDepositoWakaf::whereYear('created_at', '=', ($year-1)))->where('pengelolaan_id','!=','0')->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumKredit_kdw;

            $jumlah_kas = $saldoTerakhirKasTunaiWakif + $saldoTerakhirKasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;

        //PIUTANG
        //HASIL PENGELOLAAN
            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', ($year-1)))->sum('saldo');
            $sumKredit_pjp = PiutangJangkaPendek::whereYear('created_at', '=', ($year-1)))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumKredit_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', ($year-1)))->sum('saldo');
            $sumKredit_pja = PiutangJangkaPanjang::whereYear('created_at', '=', ($year-1)))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumKredit_pja;

        $jumlah_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

        //ASET TETAP
            //TANAH
            $sum_tanah = AkunTanah::whereYear('created_at', '=', ($year-1)))->sum('saldo');
            $sumKredit_tanah = AkunTanah::whereYear('created_at', '=', ($year-1)))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumKredit_tanah;

            //BANGUNAN
            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', ($year-1)))->sum('saldo');
            $sumKredit_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', ($year-1)))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumKredit_gedung;

            //KENDARAAN
            $kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', ($year-1)))->sum('saldo');
            $sumKredit_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', ($year-1)))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumKredit_kendaraan;

            //LAINNYA
            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', ($year-1)))->sum('saldo');
            $sumKredit_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', ($year-1)))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumKredit_aset_lain - $sumKredit_aset_lain;

            $saldo_terakhir_aset_tetap =  $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_aset_lain;

    //Jumlah aset

           $jumlah_aset = $saldo_terakhir_kas + $saldo_terakhir_piutang + $saldo_terakhir_aset_tetap;

    $array = [$saldo_terakhir_kas, $saldo_terakhir_piutang, $saldo_terakhir_aset_tetap, $jumlah_aset];
        
        /* if ($request->has('export')) {
            if ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('customers.index-pdf', compact('data'));
                return $pdf->download('customer-list.pdf');
            }
        } */

        
    return Response::HttpResponse(200, $array, "Success", false);
    }
}