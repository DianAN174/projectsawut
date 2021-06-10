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


Class LaporanAktivitas
{
    //GET YEAR
    $year = Carbon::now()->format('Y');

    public function LaporanAktivitasx2(Request $request)
    {
        //PENGHASILAN
        //Penerimaan Wakaf Permanen

            $sum_permanen = WakafTemporerPermanen::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_permanen  = WakafTemporerPermanen::whereYear('created_at', '=', $year)->whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_permanen = $sum_wtjp - $sumKredit_wtjp;

        //Penerimaan Wakaf Temporer

            //wtjp = wakaf temporer jangka pendek
            $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_wtjp = $sum_wtjp - $sumKredit_wtjp;

            //wtja = wakaf temporer jangka panjang
            $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumKredit_wtja;
        
        //Dampak Pengukuran Ulang Aset Wakaf

        //Pengelolaan dan Pengembangan Aset Wakaf

            //bagi hasil
            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumKredit_ktbh;

            //bpp = beban pengelolaan dan pengembangan wakaf
            $sum_bpp = BebanPengelolaandanPengembanganWakaf::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_bpp  = BebanPengelolaandanPengembanganWakaf::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_bpp = $sum_bpp - $sumKredit_bpp;

            //bnp = beban nazhir atas pengelolaan dan pengembangan wakaf
            $sum_bnp = BagianNazhir::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_bnp  = BagianNazhir::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_bnp = $sum_bnp - $sumKredit_bnp;

        $jumlah_penghasilan = $saldoTerakhir_permanen + $saldoTerakhir_wtjp + $saldoTerakhir_wtja + $saldoTerakhir_bpp + $saldoTerakhir_bnp;

        //BEBAN
        //Kegiatan ibadah
        $get = PengajuanBiaya::with('PentasyarufanManfaat')->where('jenis_biaya','=','ibadah')->whereYear('created_at', '=', $year)->sum('saldo');
        
            $sum_ibadah = PentasyarufanManfaat::whereYear('created_at', '=', $year)->sum('saldo');
            $sumKredit_ibadah  = PentasyarufanManfaat::whereYear('created_at', '=', $year)->where('type','=','kredit')->sum('saldo');
            $saldoTerakhir_ibadah = $sum_bnp - $sumKredit_bnp;
        //kegiatan pendidikan
        //Kegiatan kesehatan
        //kegiatan fakir miskin
        //kegiatan ekonomi umat
        //kegiatan kesejahteraan umat lain

        //KENAIKAN (PENURUNAN) ASET NETO
        //ASET NETO AWAL PERIODE
        //ASET NETO AKHIR PERIODE

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