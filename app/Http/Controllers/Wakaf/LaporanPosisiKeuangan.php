<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\DataWakif;
use App\Models\PenerimaanTunaiPermanen;
use App\Models\WakafTemporerJangkaPendek;
use App\Models\WakafTemporerJangkaPanjang;

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
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class LaporanPosisiKeuangan
{

    //GET YEAR
    

    public function LaporanPosisiKeuanganx2(Request $request)
    {

        $year = Carbon::now()->format('Y');
        
        //ASET LANCAR
        //KAS DAN SEJENIS KAS
            
            $sumKasTunai = KasTunai::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKreditTunai = KasTunai::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhirKasTunai=$sumKasTunai - $sumKreditTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumKredit_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumKredit_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumKredit_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumKredit_kdw;

            $saldo_terakhir_kas = $saldoTerakhirKasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;


        //PIUTANG
            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumKredit_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumKredit_pja;

            $saldo_terakhir_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

    //Aset Tidak Lancar
            
            $persediaan = AkunPersediaan::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_persediaan = AkunPersediaan::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_persediaan = $sumKredit_persediaan - $sumKredit_persediaan;

            $kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumKredit_kendaraan;

            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumKredit_gedung;

            $sum_tanah = AkunTanah::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_tanah = AkunTanah::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumKredit_tanah;

            $peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_peralatan = $sumKredit_peralatan - $sumKredit_peralatan;

            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumKredit_aset_lain - $sumKredit_aset_lain;

            $saldo_terakhir_aset_tetap = $saldoTerakhir_persediaan + $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_peralatan + $saldoTerakhir_aset_lain;

    //Jumlah aset

           $jumlah_aset = $saldo_terakhir_kas + $saldo_terakhir_piutang + $saldo_terakhir_aset_tetap;

    //Liabilitas Jangka Pendek
        
            //UTANG
            //utang biaya
            $biaya = UtangBiaya::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_biaya = UtangBiaya::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_biaya = $sumKredit_biaya - $sumKredit_biaya;


            //uja = utang jangka panjang
            $sum_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_pja = UtangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumKredit_pja;

            $saldo_terakhir_utang = $saldoTerakhir_biaya + $saldoTerakhir_pja;

        //Wakaf Temporer Jangka Pendek
        
            //wtjp = wakaf temporer jangka pendek
            $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_wtjp = $sum_wtjp - $sumKredit_wtjp;

    //Liabilitas Jangka Panjang

        //WAKAF TEMPORER JANGKA PANJANG
            //wtja = wakaf temporer jangka panjang
            $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumKredit_wtja;

        //JUMLAH LIABILITAS
            $jumlah_liabilitas = $saldo_terakhir_utang + $saldoTerakhir_wtjp + $saldoTerakhir_wtja;

        //JUMLAH ASET NETO
            $jumlah_aset_neto = $jumlah_aset - $jumlah_liabilitas;

        //JUMLAH LIABILITAS DAN ASET NETO
            $jumlah_liabilitas_aset_neto = $jumlah_liabilitas + $jumlah_aset_neto;

        
        $array = [$saldo_terakhir_kas, $saldo_terakhir_piutang, $saldo_terakhir_aset_tetap, $jumlah_aset, $saldo_terakhir_utang,
        $saldoTerakhir_wtjp, $saldoTerakhir_wtja, $jumlah_liabilitas, $jumlah_aset_neto, $jumlah_liabilitas_aset_neto];
        
        /* if ($request->has('export')) {
            if ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('customers.index-pdf', compact('data'));
                return $pdf->download('customer-list.pdf');
            }
        } */

        
        return Response::HttpResponse(200, $array, "Success", false);

    }
    
    public function LaporanPosisiKeuanganx1(Request $request)
    {

        //ASET LANCAR
        //KAS DAN SEJENIS KAS
            
            $sumKasTunai = KasTunai::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKreditTunai = KasTunai::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhirKasTunai=$sumKasTunai - $sumKreditTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_ktw = KasTabWakaf::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumKredit_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_ktbh = KasTabBagiHasil::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumKredit_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumKredit_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_kdw = KasDepositoWakaf::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumKredit_kdw;

            $saldo_terakhir_kas = $saldoTerakhirKasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;


        //PIUTANG
            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_pjp = PiutangJangkaPendek::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumKredit_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_pja = PiutangJangkaPanjang::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumKredit_pja;

            $saldo_terakhir_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

    //Aset Tidak Lancar
            
            $persediaan = AkunPersediaan::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_persediaan = AkunPersediaan::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_persediaan = $sumKredit_persediaan - $sumKredit_persediaan;

            $kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumKredit_kendaraan;

            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumKredit_gedung;

            $sum_tanah = AkunTanah::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_tanah = AkunTanah::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumKredit_tanah;

            $peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_peralatan = $sumKredit_peralatan - $sumKredit_peralatan;

            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumKredit_aset_lain - $sumKredit_aset_lain;

            $saldo_terakhir_aset_tetap = $saldoTerakhir_persediaan + $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_peralatan + $saldoTerakhir_aset_lain;

    //Jumlah aset

           $jumlah_aset = $saldo_terakhir_kas + $saldo_terakhir_piutang;

    //Liabilitas Jangka Pendek
        
            //UTANG
            //utang biaya
            $biaya = UtangBiaya::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_biaya = UtangBiaya::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_biaya = $sumKredit_biaya - $sumKredit_biaya;


            //uja = utang jangka panjang
            $sum_uja = UtangJangkaPanjang::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_pja = UtangJangkaPanjang::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumKredit_pja;

            $saldo_terakhir_utang = $saldoTerakhir_biaya + $saldoTerakhir_pja;

        //Wakaf Temporer Jangka Pendek
        
            //wtjp = wakaf temporer jangka pendek
            $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_wtjp = $sum_wtjp - $sumKredit_wtjp;

    //Liabilitas Jangka Panjang

        //WAKAF TEMPORER JANGKA PANJANG
            //wtja = wakaf temporer jangka panjang
            $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', ($year-1))->sum('saldo');
            $sumKredit_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', ($year-1))->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumKredit_wtja;

        //JUMLAH LIABILITAS
            $jumlah_liabilitas = $saldo_terakhir_utang + $saldoTerakhir_wtjp + $saldoTerakhir_wtja;

        //JUMLAH ASET NETO
            $jumlah_aset_neto = $jumlah_aset - $jumlah_liabilitas;

        //JUMLAH LIABILITAS DAN ASET NETO
            $jumlah_liabilitas_aset_neto = $jumlah_liabilitas + $jumlah_aset_neto;

        
        $array = [$saldo_terakhir_kas, $saldo_terakhir_piutang, $saldo_terakhir_aset_tetap, $jumlah_aset, $saldo_terakhir_utang,
        $saldoTerakhir_wtjp, $saldoTerakhir_wtja, $jumlah_liabilitas, $jumlah_aset_neto, $jumlah_liabilitas_aset_neto];
        
        /* if ($request->has('export')) {
            if ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('customers.index-pdf', compact('data'));
                return $pdf->download('customer-list.pdf');
            }
        } */

        
        return Response::HttpResponse(200, $array, "Success", false);

    }

}