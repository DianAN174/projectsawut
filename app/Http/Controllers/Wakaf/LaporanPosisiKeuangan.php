<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\DataWakif;
use App\Models\PenerimaanTunaiPermanen;
use App\Models\WakafTemporerJangkaPendek;
use App\Models\WakafTemporerJangkaPanjang;

use App\Models\ModelPengelolaan\KasTunai;
use App\Models\ModelPengelolaan\KasTabWakaf;
use App\Models\ModelPengelolaan\KasTabBagiHasil;
use App\Models\ModelPengelolaan\KasTabNonbagiHasil;
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
use App\Models\ModelPengelolaanLain\AkunGedungdanBangunan;
use App\Models\ModelPengelolaanLain\AkunTanah;
use App\Models\ModelPengelolaanLain\AkunPeralatandanPerlengkapanKantor;
use App\Models\ModelPengelolaanLain\AkunAsetLainLain;
use App\Models\ModelPengelolaanLain\DataAkumulasi;

use App\Models\User;
use App\Utils\Response;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class LaporanPosisiKeuangan
{

    /* public function Download()
    {
        $filePath = public_path("dummy.pdf");
    	$headers = ['Content-Type' => 'application/pdf'];
    	$fileName = time().'.pdf';

    	return response()->download($filePath, $fileName, $headers);
        } */

    public function Generate()
    {
        $year1=2020;
        $year2=2021;
        $periode_lalu = $this->LaporanPosisiKeuanganx1();
        $periode_ini = $this->LaporanPosisiKeuanganx2();
        $data = [
                'year1' => (string) $year1,
                'year2' => (string) $year2,
                'kastunai1' => (string) $periode_lalu[0],
                'kastunai' => (string) $periode_ini[0],
                'kastabwakaf1' => (string) $periode_lalu[1],
                'kastabwakaf' => (string) $periode_ini[1],
                'kastabbagihasil1' =>  (string) $periode_lalu[2],
                'kastabbagihasil' =>  (string) $periode_ini[2],
                'kastabnonbagihasil1' =>  (string) $periode_lalu[3],
                'kastabnonbagihasil' =>  (string) $periode_ini[3],
                'kasdeposito1' => (string) $periode_lalu[4],
                'kasdeposito' => (string) $periode_ini[4],
                'pjp1' => (string) $periode_lalu[5],
                'pjp' => (string) $periode_ini[5],
                'pja1' => (string) $periode_lalu[6],
                'pja' => (string) $periode_ini[6],
                'persediaan1' => (string) $periode_lalu[7],
                'persediaan' => (string) $periode_ini[7],
                'tanah1' => (string) $periode_lalu[8],
                'tanah' => (string) $periode_ini[8],
                'gedung1' => (string) $periode_lalu[9],
                'gedung' => (string) $periode_ini[9],
                'mesin1' => (string) $periode_lalu[10],
                'mesin' => (string) $periode_ini[10],
                'peralatan1' => (string) $periode_lalu[11],
                'peralatan' => (string) $periode_ini[11],
                'asetlain1' => (string) $periode_lalu[12],
                'asetlain' => (string) $periode_ini[12],
                'haksewa1' => (string) $periode_lalu[13],
                'haksewa' => (string) $periode_ini[13],
                'totalaset1' => (string) $periode_lalu[14],
                'totalaset' => (string) $periode_ini[14],
                'wtjp1' => (string) $periode_lalu[15],
                'wtjp' => (string) $periode_ini[15],
                'utangbiaya1' => (string) $periode_lalu[16],
                'utangbiaya' => (string) $periode_ini[16],
                'wtja1' => (string) $periode_lalu[17],
                'wtja' => (string) $periode_ini[17],
                'uja1' => (string) $periode_lalu[18],
                'uja' => (string) $periode_ini[18],
                'total_liabilitas1' => (string) $periode_lalu[19],
                'total_liabilitas' => (string) $periode_ini[19],
                'aset_neto1' => (string) $periode_lalu[20],
                'aset_neto' => (string) $periode_ini[20],
                'jml_total_aset_neto1' => (string) $periode_lalu[21],
                'jml_total_aset_neto' => (string) $periode_ini[21],
                'jml_liabilitas_aset_neto1' => (string) $periode_lalu[22],
                'jml_liabilitas_aset_neto' => (string) $periode_ini[22]
            ];
            
        $pdf = PDF::loadView('LaporanPosisiKeuangan', $data);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('LaporanPosisiKeuangan.pdf',array("Attachment"=>0));
    }
    
        
    public function Download()
    {
        $year1=2020;
        $year2=2021;
        $periode_lalu = $this->LaporanPosisiKeuanganx1();
        $periode_ini = $this->LaporanPosisiKeuanganx2();
        $data = [
            'year1' => (string) $year1,
            'year2' => (string) $year2,
            'kastunai1' => (string) $periode_lalu[0],
            'kastunai' => (string) $periode_ini[0],
            'kastabwakaf1' => (string) $periode_lalu[1],
            'kastabwakaf' => (string) $periode_ini[1],
            'kastabbagihasil1' =>  (string) $periode_lalu[2],
            'kastabbagihasil' =>  (string) $periode_ini[2],
            'kastabnonbagihasil1' =>  (string) $periode_lalu[3],
            'kastabnonbagihasil' =>  (string) $periode_ini[3],
            'kasdeposito1' => (string) $periode_lalu[4],
            'kasdeposito' => (string) $periode_ini[4],
            'pjp1' => (string) $periode_lalu[5],
            'pjp' => (string) $periode_ini[5],
            'pja1' => (string) $periode_lalu[6],
            'pja' => (string) $periode_ini[6],
            'persediaan1' => (string) $periode_lalu[7],
            'persediaan' => (string) $periode_ini[7],
            'tanah1' => (string) $periode_lalu[8],
            'tanah' => (string) $periode_ini[8],
            'gedung1' => (string) $periode_lalu[9],
            'gedung' => (string) $periode_ini[9],
            'mesin1' => (string) $periode_lalu[10],
            'mesin' => (string) $periode_ini[10],
            'peralatan1' => (string) $periode_lalu[11],
            'peralatan' => (string) $periode_ini[11],
            'asetlain1' => (string) $periode_lalu[12],
            'asetlain' => (string) $periode_ini[12],
            'haksewa1' => (string) $periode_lalu[13],
            'haksewa' => (string) $periode_ini[13],
            'totalaset1' => (string) $periode_lalu[14],
            'totalaset' => (string) $periode_ini[14],
            'wtjp1' => (string) $periode_lalu[15],
            'wtjp' => (string) $periode_ini[15],
            'utangbiaya1' => (string) $periode_lalu[16],
            'utangbiaya' => (string) $periode_ini[16],
            'wtja1' => (string) $periode_lalu[17],
            'wtja' => (string) $periode_ini[17],
            'uja1' => (string) $periode_lalu[18],
            'uja' => (string) $periode_ini[18],
            'total_liabilitas1' => (string) $periode_lalu[19],
            'total_liabilitas' => (string) $periode_ini[19],
            'aset_neto1' => (string) $periode_lalu[20],
            'aset_neto' => (string) $periode_ini[20],
            'jml_total_aset_neto1' => (string) $periode_lalu[21],
            'jml_total_aset_neto' => (string) $periode_ini[21],
            'jml_liabilitas_aset_neto1' => (string) $periode_lalu[22],
            'jml_liabilitas_aset_neto' => (string) $periode_ini[22]
        ];

        $headers = ['Content-Type' => 'application/pdf'];

        $pdf = PDF::loadView('LaporanPosisiKeuangan', $data);
        $pdf->setPaper('A4', 'potrait');
            //return $pdf->download('LaporanPosisiKeuangan.pdf');
        return $pdf->download('LaporanPosisiKeuangan.pdf', $headers);
    }

    public function LaporanPosisiKeuanganx2()
    {

        $year = Carbon::now()->format('Y');
        //ASET
        //ASET LANCAR
            //Kas Tunai
            $sumKasTunai = KasTunai::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaranTunai = KasTunai::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_KasTunai=$sumKasTunai - $sumPengeluaranTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumPengeluaran_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumPengeluaran_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonbagiHasil::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbnh = KasTabNonbagiHasil::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumPengeluaran_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumPengeluaran_kdw;

            $saldo_terakhir_kas = $saldoTerakhir_KasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;

            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumPengeluaran_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumPengeluaran_pja;

            $saldo_terakhir_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

            //Persediaan
            /*$persediaan = AkunPersediaan::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_persediaan = AkunPersediaan::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_persediaan = $sumPengeluaran_persediaan - $sumPengeluaran_persediaan;
            */
            //akun persediaan nerima input dari mana?
            $saldoTerakhir_persediaan = 0;
            
    //ASET TIDAK LANCAR
            //ASET TETAP
            $sum_tanah = AkunTanah::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_tanah = AkunTanah::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumPengeluaran_tanah;

            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumPengeluaran_gedung;

            $sum_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumPengeluaran_kendaraan;

            $peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_peralatan = $sumPengeluaran_peralatan - $sumPengeluaran_peralatan;

            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumPengeluaran_aset_lain - $sumPengeluaran_aset_lain;

            $saldo_terakhir_aset_tetap = $saldoTerakhir_persediaan + $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_peralatan + $saldoTerakhir_aset_lain;

    //ASET TIDAK LANCAR LAINNYA
    //Hak Sewa
    $saldoTerakhir_hak_sewa = 0;

    //TOTAL ASET

        $jumlah_aset = $saldo_terakhir_kas + $saldo_terakhir_piutang + $saldoTerakhir_persediaan + $saldo_terakhir_aset_tetap + $saldoTerakhir_hak_sewa;


    //LIABILITAS
    //LIABILITAS JANGKA PENDEK
        
            //Wakaf Temporer Jangka Pendek
            //wtjp = wakaf temporer jangka pendek
            $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtjp = $sum_wtjp - $sumPengeluaran_wtjp;

            //UTANG
            //utang biaya
            $biaya = UtangBiaya::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_biaya = UtangBiaya::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_biaya = $sumPengeluaran_biaya - $sumPengeluaran_biaya;
            
            //$saldo_terakhir_utang = $saldoTerakhir_biaya + $saldoTerakhir_pja;

    //Liabilitas Jangka Panjang

            //WAKAF TEMPORER JANGKA PANJANG
            //wtja = wakaf temporer jangka panjang
            $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumPengeluaran_wtja;

            //UTANG JANGKA PANJANG
            //uja = utang jangka panjang
            $sum_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_uja = $sum_uja - $sumPengeluaran_pja;

    //JUMLAH TOTAL LIABILITAS
            $jumlah_liabilitas = $saldoTerakhir_biaya + $saldoTerakhir_wtjp + $saldoTerakhir_uja + $saldoTerakhir_wtja;

    //ASET NETO
        //aset neto wakaf
            $jumlah_aset_neto = $jumlah_aset - $jumlah_liabilitas;

    //JUMLAH TOTAL ASET NETO WAKAF
            $jumlah_total_aset_neto = $jumlah_aset - $jumlah_liabilitas;
        
    //JUMLAH LIABILITAS DAN ASET NETO
            $jumlah_liabilitas_aset_neto = $jumlah_liabilitas + $jumlah_aset_neto;

        
        $array = [$saldoTerakhir_KasTunai,$saldoTerakhir_ktw,$saldoTerakhir_ktbh,$saldoTerakhir_ktbnh,
        $saldoTerakhir_kdw,$saldoTerakhir_pjp,$saldoTerakhir_pja,$saldoTerakhir_persediaan,
        $saldoTerakhir_tanah,$saldoTerakhir_gedung,$saldoTerakhir_kendaraan,$saldoTerakhir_peralatan,
        $saldoTerakhir_aset_lain,$saldoTerakhir_hak_sewa,$jumlah_aset,
        $saldoTerakhir_wtjp,$saldoTerakhir_biaya,$saldoTerakhir_wtja,$saldoTerakhir_uja,
        $jumlah_liabilitas,$jumlah_aset_neto,$jumlah_total_aset_neto,$jumlah_liabilitas_aset_neto];
        /* if ($request->has('export')) {
            if ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('customers.index-pdf', compact('data'));
                return $pdf->download('customer-list.pdf');
            }
        } */

        
        //return Response::HttpResponse(200, $array, "Success", false);
        return $array;
    }
    
    public function LaporanPosisiKeuanganx1()
    {

        $year = Carbon::now()->format('Y');
        //ASET
        //ASET LANCAR
            //Kas Tunai
            $sumKasTunai = KasTunai::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaranTunai = KasTunai::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_KasTunai=$sumKasTunai - $sumPengeluaranTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktw = KasTabWakaf::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumPengeluaran_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumPengeluaran_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonbagiHasil::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbnh = KasTabNonbagiHasil::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumPengeluaran_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumPengeluaran_kdw;

            $saldo_terakhir_kas = $saldoTerakhir_KasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;

            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumPengeluaran_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumPengeluaran_pja;

            $saldo_terakhir_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

            //Persediaan
            /*$persediaan = AkunPersediaan::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_persediaan = AkunPersediaan::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_persediaan = $sumPengeluaran_persediaan - $sumPengeluaran_persediaan;
            */
            //akun persediaan nerima input dari mana?
            $saldoTerakhir_persediaan = 0;
            
    //ASET TIDAK LANCAR
            //ASET TETAP
            $sum_tanah = AkunTanah::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_tanah = AkunTanah::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumPengeluaran_tanah;

            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumPengeluaran_gedung;

            $sum_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumPengeluaran_kendaraan;

            $peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_peralatan = AkunPeralatandanPerlengkapanKantor::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_peralatan = $sumPengeluaran_peralatan - $sumPengeluaran_peralatan;

            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumPengeluaran_aset_lain - $sumPengeluaran_aset_lain;

            $saldo_terakhir_aset_tetap = $saldoTerakhir_persediaan + $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_peralatan + $saldoTerakhir_aset_lain;

    //ASET TIDAK LANCAR LAINNYA
    //Hak Sewa
    $saldoTerakhir_hak_sewa = 0;

    //TOTAL ASET

        $jumlah_aset = $saldo_terakhir_kas + $saldo_terakhir_piutang + $saldoTerakhir_persediaan + $saldo_terakhir_aset_tetap + $saldoTerakhir_hak_sewa;


    //LIABILITAS
    //LIABILITAS JANGKA PENDEK
        
            //Wakaf Temporer Jangka Pendek
            //wtjp = wakaf temporer jangka pendek
            $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtjp = $sum_wtjp - $sumPengeluaran_wtjp;

            //UTANG
            //utang biaya
            $biaya = UtangBiaya::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_biaya = UtangBiaya::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_biaya = $sumPengeluaran_biaya - $sumPengeluaran_biaya;
            
            //$saldo_terakhir_utang = $saldoTerakhir_biaya + $saldoTerakhir_pja;

    //Liabilitas Jangka Panjang

            //WAKAF TEMPORER JANGKA PANJANG
            //wtja = wakaf temporer jangka panjang
            $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumPengeluaran_wtja;

            //UTANG JANGKA PANJANG
            //uja = utang jangka panjang
            $sum_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_uja = $sum_uja - $sumPengeluaran_pja;

    //JUMLAH TOTAL LIABILITAS
            $jumlah_liabilitas = $saldoTerakhir_biaya + $saldoTerakhir_wtjp + $saldoTerakhir_uja + $saldoTerakhir_wtja;

    //ASET NETO
        //aset neto wakaf
            $jumlah_aset_neto = $jumlah_aset - $jumlah_liabilitas;

    //JUMLAH TOTAL ASET NETO WAKAF
            $jumlah_total_aset_neto = $jumlah_aset - $jumlah_liabilitas;
        
    //JUMLAH LIABILITAS DAN ASET NETO
            $jumlah_liabilitas_aset_neto = $jumlah_liabilitas + $jumlah_aset_neto;

        
        $array = [$saldoTerakhir_KasTunai,$saldoTerakhir_ktw,$saldoTerakhir_ktbh,$saldoTerakhir_ktbnh,$saldoTerakhir_kdw,$saldo_terakhir_kas,$saldoTerakhir_pjp,$saldoTerakhir_pja,$saldo_terakhir_piutang,
        $saldoTerakhir_kendaraan,$saldoTerakhir_gedung,$saldoTerakhir_tanah,$saldoTerakhir_peralatan,$saldoTerakhir_aset_lain,$saldo_terakhir_aset_tetap,$saldoTerakhir_hak_sewa,$jumlah_aset,
        $saldoTerakhir_wtjp,$saldoTerakhir_biaya,$saldoTerakhir_wtja,$saldoTerakhir_uja,$jumlah_liabilitas,$jumlah_aset_neto,$jumlah_total_aset_neto,$jumlah_liabilitas_aset_neto];
        /* if ($request->has('export')) {
            if ($request->get('export') == 'pdf') {
                $pdf = PDF::loadView('customers.index-pdf', compact('data'));
                return $pdf->download('customer-list.pdf');
            }
        } */

        
        //return Response::HttpResponse(200, $array, "Success", false);
        return $array;
    }

}