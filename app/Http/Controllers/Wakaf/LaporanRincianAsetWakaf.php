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
use App\Models\ModelPengelolaan\KasTabWakaf;
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


Class LaporanRincianAsetWakaf
{
    public function Generate()
    {
        $year1=2020;
        $year2=2021;
        $periode_lalu = $this->LaporanRincianAsetWakafx1();
        $periode_ini = $this->LaporanRincianAsetWakafx2();
        $data = [
            'title' => 'Laporan Rincian Aset Wakaf',
            'year1' => (string) $year1,
            'year2' => (string) $year2,
            'kasWakif1' => (string) $periode_lalu[0],
            'kasHasil1' => (string) $periode_lalu[1],
            'kasJumlah1' => (string) $periode_lalu[2],
            'kasWakif2' => (string) $periode_ini[0],
            'kasHasil2' => (string) $periode_ini[1],
            'kasJumlah2' => (string) $periode_ini[2],
            'piutangHasil1' => (string) $periode_lalu[3],
            'piutangJumlah1' =>  (string) $periode_lalu[4],
            'piutangHasil2' => (string) $periode_ini[3],
            'piutangJumlah2' =>  (string) $periode_ini[4],
            'bilyetDepositoWakif1' => (string) $periode_lalu[5],
            'bilyetDepositoHasil1' => (string) $periode_lalu[6],
            'bilyetDepositoJumlah1' => (string) $periode_lalu[7],
            'bilyetDepositoWakif2' => (string) $periode_ini[5],
            'bilyetDepositoHasil2' => (string) $periode_ini[6],
            'bilyetDepositoJumlah2' => (string) $periode_ini[7],
            'logamMuliaWakif1' => (string) $periode_lalu[8],
            'logamMuliaHasil1' => (string) $periode_lalu[9],
            'logamMuliaJumlah1' => (string) $periode_lalu[10],
            'logamMuliaWakif2' => (string) $periode_ini[8],
            'logamMuliaHasil2' => (string) $periode_ini[9],
            'logamMuliaJumlah2' => (string) $periode_ini[10],
            'asetLancarLainWakif1' => (string) $periode_lalu[11],
            'asetLancarLainHasil1' => (string) $periode_lalu[12],
            'asetLancarLainJumlah1' => (string) $periode_lalu[13],
            'asetLancarLainWakif2' => (string) $periode_ini[11],
            'asetLancarLainHasil2' => (string) $periode_ini[12],
            'asetLancarLainJumlah2' => (string) $periode_ini[13],
            'hakSewaWakif1' => (string) $periode_lalu[14],
            'hakSewaHasil1' => (string) $periode_lalu[15],
            'hakSewaJumlah1' => (string) $periode_lalu[16],
            'hakSewaWakif2' => (string) $periode_ini[14],
            'hakSewaHasil2' => (string) $periode_ini[15],
            'hakSewaJumlah2' => (string) $periode_ini[16],
            'investasiPadaEntitasLainWakif1' => (string) $periode_lalu[17],
            'investasiPadaEntitasLainHasil1' => (string) $periode_lalu[18],
            'investasiPadaEntitasLainJumlah1' => (string) $periode_lalu[19],
            'investasiPadaEntitasLainWakif2' => (string) $periode_ini[17],
            'investasiPadaEntitasLainHasil2' => (string) $periode_ini[18],
            'investasiPadaEntitasLainJumlah2' => (string) $periode_ini[19],
            'hakTanahWakif1' => (string) $periode_lalu[20],
            'hakTanahHasil1' => (string) $periode_lalu[21],
            'hakTanahJumlah1' => (string) $periode_lalu[22],
            'hakTanahWakif2' => (string) $periode_ini[20],
            'hakTanahHasil2' => (string) $periode_ini[21],
            'hakTanahJumlah2' => (string) $periode_ini[22],
            'bangunanWakif1' => (string) $periode_lalu[23],
            'bangunanHasil1' => (string) $periode_lalu[24],
            'bangunanJumlah1' => (string) $periode_lalu[25],
            'bangunanWakif2' => (string) $periode_ini[23],
            'bangunanHasil2' => (string) $periode_ini[24],
            'bangunanJumlah2' => (string) $periode_ini[25],
            'hakMilikRumahWakif1' => (string) $periode_lalu[26],
            'hakMilikRumahHasil1' => (string) $periode_lalu[27],
            'hakMilikRumahJumlah1' => (string) $periode_lalu[28],
            'hakMilikRumahWakif2' => (string) $periode_ini[26],
            'hakMilikRumahHasil2' => (string) $periode_ini[27],
            'hakMilikRumahJumlah2' => (string) $periode_ini[28],
            'kendaraanWakif1' => (string) $periode_lalu[29],
            'kendaraanHasil1' => (string) $periode_lalu[30],
            'kendaraanJumlah1' => (string) $periode_lalu[31],
            'kendaraanWakif2' => (string) $periode_ini[29],
            'kendaraanHasil2' => (string) $periode_ini[30],
            'kendaraanJumlah2' => (string) $periode_ini[31],
            'lainnyaWakif1' => (string) $periode_lalu[32],
            'lainnyaHasil1' => (string) $periode_lalu[33],
            'lainnyaJumlah1' => (string) $periode_lalu[34],
            'lainnyaWakif2' => (string) $periode_ini[32],
            'lainnyaHasil2' => (string) $periode_ini[33],
            'lainnyaJumlah2' => (string) $periode_ini[34],
            'HKIWakif1' => (string) $periode_lalu[35],
            'HKIHasil1' => (string) $periode_lalu[36],
            'HKIJumlah1' => (string) $periode_lalu[37],
            'HKIWakif2' => (string) $periode_ini[35],
            'HKIHasil2' => (string) $periode_ini[36],
            'HKIJumlah2' => (string) $periode_ini[37],
            'asetTidakLancarLainWakif1' => (string) $periode_lalu[38],
            'asetTidakLancarLainHasil1' => (string) $periode_lalu[39],
            'asetTidakLancarLainJumlah1' => (string) $periode_lalu[40],
            'asetTidakLancarLainWakif2' => (string) $periode_ini[38],
            'asetTidakLancarLainHasil2' => (string) $periode_ini[39],
            'asetTidakLancarLainJumlah2' => (string) $periode_ini[40],
            'jumlahAsetWakif1' => (string) $periode_lalu[41],
            'jumlahAsetHasil1' => (string) $periode_lalu[42],
            'jumlahAset1' => (string) $periode_lalu[43],
            'jumlahAsetWakif2' => (string) $periode_ini[41],
            'jumlahAsetHasil2' => (string) $periode_ini[42],
            'jumlahAset2' => (string) $periode_ini[43]
        ];

        $headers = ['Content-Type' => 'application/pdf'];

        $pdf = PDF::loadView('LaporanRincianAsetWakaf', $data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->stream('LaporanRincianAsetWakaf.pdf',array("Attachment"=>0, $headers));
    }

    public function Download()
    {
        $year1=2020;
        $year2=2021;
        $periode_lalu = $this->LaporanRincianAsetWakafx1();
        $periode_ini = $this->LaporanRincianAsetWakafx2();
        $data = [
            'title' => 'Laporan Rincian Aset Wakaf',
            'year1' => (string) $year1,
            'year2' => (string) $year2,
            'kasWakif1' => (string) $periode_lalu[0],
            'kasHasil1' => (string) $periode_lalu[1],
            'kasJumlah1' => (string) $periode_lalu[2],
            'kasWakif2' => (string) $periode_ini[0],
            'kasHasil2' => (string) $periode_ini[1],
            'kasJumlah2' => (string) $periode_ini[2],
            'piutangHasil1' => (string) $periode_lalu[3],
            'piutangJumlah1' =>  (string) $periode_lalu[4],
            'piutangHasil2' => (string) $periode_ini[3],
            'piutangJumlah2' =>  (string) $periode_ini[4],
            'bilyetDepositoWakif1' => (string) $periode_lalu[5],
            'bilyetDepositoHasil1' => (string) $periode_lalu[6],
            'bilyetDepositoJumlah1' => (string) $periode_lalu[7],
            'bilyetDepositoWakif2' => (string) $periode_ini[5],
            'bilyetDepositoHasil2' => (string) $periode_ini[6],
            'bilyetDepositoJumlah2' => (string) $periode_ini[7],
            'logamMuliaWakif1' => (string) $periode_lalu[8],
            'logamMuliaHasil1' => (string) $periode_lalu[9],
            'logamMuliaJumlah1' => (string) $periode_lalu[10],
            'logamMuliaWakif2' => (string) $periode_ini[8],
            'logamMuliaHasil2' => (string) $periode_ini[9],
            'logamMuliaJumlah2' => (string) $periode_ini[10],
            'asetLancarLainWakif1' => (string) $periode_lalu[11],
            'asetLancarLainHasil1' => (string) $periode_lalu[12],
            'asetLancarLainJumlah1' => (string) $periode_lalu[13],
            'asetLancarLainWakif2' => (string) $periode_ini[11],
            'asetLancarLainHasil2' => (string) $periode_ini[12],
            'asetLancarLainJumlah2' => (string) $periode_ini[13],
            'hakSewaWakif1' => (string) $periode_lalu[14],
            'hakSewaHasil1' => (string) $periode_lalu[15],
            'hakSewaJumlah1' => (string) $periode_lalu[16],
            'hakSewaWakif2' => (string) $periode_ini[14],
            'hakSewaHasil2' => (string) $periode_ini[15],
            'hakSewaJumlah2' => (string) $periode_ini[16],
            'investasiPadaEntitasLainWakif1' => (string) $periode_lalu[17],
            'investasiPadaEntitasLainHasil1' => (string) $periode_lalu[18],
            'investasiPadaEntitasLainJumlah1' => (string) $periode_lalu[19],
            'investasiPadaEntitasLainWakif2' => (string) $periode_ini[17],
            'investasiPadaEntitasLainHasil2' => (string) $periode_ini[18],
            'investasiPadaEntitasLainJumlah2' => (string) $periode_ini[19],
            'hakTanahWakif1' => (string) $periode_lalu[20],
            'hakTanahHasil1' => (string) $periode_lalu[21],
            'hakTanahJumlah1' => (string) $periode_lalu[22],
            'hakTanahWakif2' => (string) $periode_ini[20],
            'hakTanahHasil2' => (string) $periode_ini[21],
            'hakTanahJumlah2' => (string) $periode_ini[22],
            'bangunanWakif1' => (string) $periode_lalu[23],
            'bangunanHasil1' => (string) $periode_lalu[24],
            'bangunanJumlah1' => (string) $periode_lalu[25],
            'bangunanWakif2' => (string) $periode_ini[23],
            'bangunanHasil2' => (string) $periode_ini[24],
            'bangunanJumlah2' => (string) $periode_ini[25],
            'hakMilikRumahWakif1' => (string) $periode_lalu[26],
            'hakMilikRumahHasil1' => (string) $periode_lalu[27],
            'hakMilikRumahJumlah1' => (string) $periode_lalu[28],
            'hakMilikRumahWakif2' => (string) $periode_ini[26],
            'hakMilikRumahHasil2' => (string) $periode_ini[27],
            'hakMilikRumahJumlah2' => (string) $periode_ini[28],
            'kendaraanWakif1' => (string) $periode_lalu[29],
            'kendaraanHasil1' => (string) $periode_lalu[30],
            'kendaraanJumlah1' => (string) $periode_lalu[31],
            'kendaraanWakif2' => (string) $periode_ini[29],
            'kendaraanHasil2' => (string) $periode_ini[30],
            'kendaraanJumlah2' => (string) $periode_ini[31],
            'lainnyaWakif1' => (string) $periode_lalu[32],
            'lainnyaHasil1' => (string) $periode_lalu[33],
            'lainnyaJumlah1' => (string) $periode_lalu[34],
            'lainnyaWakif2' => (string) $periode_ini[32],
            'lainnyaHasil2' => (string) $periode_ini[33],
            'lainnyaJumlah2' => (string) $periode_ini[34],
            'HKIWakif1' => (string) $periode_lalu[35],
            'HKIHasil1' => (string) $periode_lalu[36],
            'HKIJumlah1' => (string) $periode_lalu[37],
            'HKIWakif2' => (string) $periode_ini[35],
            'HKIHasil2' => (string) $periode_ini[36],
            'HKIJumlah2' => (string) $periode_ini[37],
            'asetTidakLancarLainWakif1' => (string) $periode_lalu[38],
            'asetTidakLancarLainHasil1' => (string) $periode_lalu[39],
            'asetTidakLancarLainJumlah1' => (string) $periode_lalu[40],
            'asetTidakLancarLainWakif2' => (string) $periode_ini[38],
            'asetTidakLancarLainHasil2' => (string) $periode_ini[39],
            'asetTidakLancarLainJumlah2' => (string) $periode_ini[40],
            'jumlahAsetWakif1' => (string) $periode_lalu[41],
            'jumlahAsetHasil1' => (string) $periode_lalu[42],
            'jumlahAset1' => (string) $periode_lalu[43],
            'jumlahAsetWakif2' => (string) $periode_ini[41],
            'jumlahAsetHasil2' => (string) $periode_ini[42],
            'jumlahAset2' => (string) $periode_ini[43]
        ];

        $headers = ['Content-Type' => 'application/pdf'];

        $pdf = PDF::loadView('LaporanRincianAsetWakaf', $data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->download('LaporanRincianAsetWakaf.pdf', $headers);
    }

    public function LaporanRincianAsetWakafx2()
    {
        $year = Carbon::now()->format('Y');

        //KAS DAN SETARA KAS
            //WAKIF
            //tunai
            $sumKasTunaiWakif = KasTunai::whereYear('created_at', '=', $year)->where('data_wakif_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaranTunaiWakif = KasTunai::whereYear('created_at', '=', $year)->where('data_wakif_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhirKasTunai_Wakif =$sumKasTunaiWakif - $sumPengeluaranTunaiWakif;

            //HASIL PENGELOLAAN
            //tunai
            $sumKasTunai = KasTunai::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaranTunai = KasTunai::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhirKasTunai = $sumKasTunai - $sumPengeluaranTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumPengeluaran_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumPengeluaran_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumPengeluaran_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumPengeluaran_kdw;

            $jumlah_wakif_kas = $saldoTerakhirKasTunai_Wakif;
            $jumlah_hasil_pengelolaan_kas = $saldoTerakhirKasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;
            $jumlah_kas = $jumlah_wakif_kas + $jumlah_hasil_pengelolaan_kas;

        //PIUTANG
        //HASIL PENGELOLAAN
            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumPengeluaran_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumPengeluaran_pja;

            //hasil pengelolaan dan jumlah piutang
            $jumlah_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

        //BILYET DEPOSITO
        $saldoTerakhirBilyetDeposito_Wakif = 0;
        $saldoTerakhirBilyetDeposito_Hasil = 0;
        $saldoTerakhirBilyetDeposito_Jumlah = $saldoTerakhirBilyetDeposito_Wakif + $saldoTerakhirBilyetDeposito_Hasil;

        //LOGAM MULIA
        $saldoTerakhirLogamMulia_Wakif = 0;
        $saldoTerakhirLogamMulia_Hasil = 0;
        $saldoTerakhirLogamMulia_Jumlah = $saldoTerakhirLogamMulia_Wakif + $saldoTerakhirLogamMulia_Hasil;
        
        //ASET LANCAR LAIN
        $saldoTerakhirAsetLancarLain_Wakif = 0;
        $saldoTerakhirAsetLancarLain_Hasil = 0;
        $saldoTerakhirAsetLancarLain_Jumlah = $saldoTerakhirAsetLancarLain_Wakif + $saldoTerakhirAsetLancarLain_Hasil;

        //HAK SEWA
        $saldoTerakhirHakSewa_Wakif = 0;
        $saldoTerakhirHakSewa_Hasil = 0;
        $saldoTerakhirHakSewa_Jumlah = $saldoTerakhirHakSewa_Wakif + $saldoTerakhirHakSewa_Hasil;
        
        //INVESTASI PADA ENTITAS LAIN
        $saldoTerakhirInvestasiEntitasLain_Wakif = 0;
        $saldoTerakhirInvestasiEntitasLain_Hasil = 0;
        $saldoTerakhirInvestasiEntitasLain_Jumlah = $saldoTerakhirInvestasiEntitasLain_Wakif + $saldoTerakhirInvestasiEntitasLain_Hasil;

        //ASET TETAP
        $saldoTerakhirTanah_Wakif = 0;
        $saldoTerakhirTanah_Hasil = 0;
        $saldoTerakhirTanah_Jumlah = $saldoTerakhirTanah_Wakif + $saldoTerakhirTanah_Hasil;

        $saldoTerakhirBangunan_Wakif = 0;
        $saldoTerakhirBangunan_Hasil = 0;
        $saldoTerakhirBangunan_Jumlah = $saldoTerakhirBangunan_Wakif + $saldoTerakhirBangunan_Hasil;

        $saldoTerakhirRumah_Wakif = 0;
        $saldoTerakhirRumah_Hasil = 0;
        $saldoTerakhirRumah_Jumlah = $saldoTerakhirRumah_Wakif + $saldoTerakhirRumah_Hasil;

        $saldoTerakhirKendaraan_Wakif = 0;
        $saldoTerakhirKendaraan_Hasil = 0;
        $saldoTerakhirKendaraan_Jumlah = $saldoTerakhirKendaraan_Wakif + $saldoTerakhirKendaraan_Hasil;

        $saldoTerakhirLainnya_Wakif = 0;
        $saldoTerakhirLainnya_Hasil = 0;
        $saldoTerakhirLainnya_Jumlah = $saldoTerakhirLainnya_Wakif + $saldoTerakhirLainnya_Hasil;

        $saldoTerakhirHKI_Wakif = 0;
        $saldoTerakhirHKI_Hasil = 0;
        $saldoTerakhirHKI_Jumlah = $saldoTerakhirHKI_Wakif + $saldoTerakhirHKI_Hasil;

        $saldoTerakhirAsetTidakLancarLain_Wakif = 0;
        $saldoTerakhirAsetTidakLancarLain_Hasil = 0;
        $saldoTerakhirAsetTidakLancarLain_Jumlah = $saldoTerakhirAsetTidakLancarLain_Wakif + $saldoTerakhirAsetTidakLancarLain_Hasil;

            /* //TANAH
            $sum_tanah = AkunTanah::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_tanah = AkunTanah::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumPengeluaran_tanah;

            //BANGUNAN
            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumPengeluaran_gedung;

            //KENDARAAN
            $kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumPengeluaran_kendaraan;

            //LAINNYA
            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumPengeluaran_aset_lain - $sumPengeluaran_aset_lain; 

            $saldo_terakhir_aset_tetap =  $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_aset_lain; */

        //Jumlah aset

        $jumlah_aset_wakif = $saldoTerakhirKasTunai_Wakif + $saldoTerakhirBilyetDeposito_Wakif + $saldoTerakhirLogamMulia_Wakif 
        + $saldoTerakhirAsetLancarLain_Wakif + $saldoTerakhirHakSewa_Wakif + 
        $saldoTerakhirInvestasiEntitasLain_Wakif + $saldoTerakhirTanah_Wakif + $saldoTerakhirBangunan_Wakif + 
        $saldoTerakhirRumah_Wakif + $saldoTerakhirKendaraan_Wakif + $saldoTerakhirLainnya_Wakif 
        + $saldoTerakhirHKI_Wakif + $saldoTerakhirAsetTidakLancarLain_Wakif;

        $jumlah_aset_hasil = $jumlah_hasil_pengelolaan_kas + $jumlah_piutang + $saldoTerakhirBilyetDeposito_Hasil + $saldoTerakhirLogamMulia_Hasil 
        + $saldoTerakhirAsetLancarLain_Hasil + $saldoTerakhirHakSewa_Hasil + 
        $saldoTerakhirInvestasiEntitasLain_Hasil + $saldoTerakhirTanah_Hasil + $saldoTerakhirBangunan_Hasil + 
        $saldoTerakhirRumah_Hasil + $saldoTerakhirKendaraan_Hasil + $saldoTerakhirLainnya_Hasil 
        + $saldoTerakhirHKI_Hasil + $saldoTerakhirAsetTidakLancarLain_Hasil;

        $jumlah_aset = $jumlah_kas + $jumlah_piutang + $saldoTerakhirBilyetDeposito_Jumlah + $saldoTerakhirLogamMulia_Jumlah 
        + $saldoTerakhirAsetLancarLain_Jumlah + $saldoTerakhirHakSewa_Jumlah + 
        $saldoTerakhirInvestasiEntitasLain_Jumlah + $saldoTerakhirTanah_Jumlah + $saldoTerakhirBangunan_Jumlah + 
        $saldoTerakhirRumah_Jumlah + $saldoTerakhirKendaraan_Jumlah + $saldoTerakhirLainnya_Jumlah 
        + $saldoTerakhirHKI_Jumlah + $saldoTerakhirAsetTidakLancarLain_Jumlah;

        $array = [$saldoTerakhirKasTunai_Wakif, $jumlah_hasil_pengelolaan_kas, $jumlah_kas, $jumlah_piutang, $jumlah_piutang, 
        $saldoTerakhirBilyetDeposito_Wakif, $saldoTerakhirBilyetDeposito_Hasil, $saldoTerakhirBilyetDeposito_Jumlah, 
        $saldoTerakhirLogamMulia_Wakif, $saldoTerakhirLogamMulia_Hasil, $saldoTerakhirLogamMulia_Jumlah, 
        $saldoTerakhirAsetLancarLain_Wakif, $saldoTerakhirAsetLancarLain_Hasil, $saldoTerakhirAsetLancarLain_Jumlah, 
        $saldoTerakhirHakSewa_Wakif, $saldoTerakhirHakSewa_Hasil, $saldoTerakhirHakSewa_Jumlah, 
        $saldoTerakhirInvestasiEntitasLain_Wakif, $saldoTerakhirInvestasiEntitasLain_Hasil, $saldoTerakhirInvestasiEntitasLain_Jumlah, 
        $saldoTerakhirTanah_Wakif, $saldoTerakhirTanah_Hasil, $saldoTerakhirTanah_Jumlah, 
        $saldoTerakhirBangunan_Wakif, $saldoTerakhirBangunan_Hasil, $saldoTerakhirBangunan_Jumlah, 
        $saldoTerakhirRumah_Wakif, $saldoTerakhirRumah_Hasil, $saldoTerakhirRumah_Jumlah, 
        $saldoTerakhirKendaraan_Wakif, $saldoTerakhirKendaraan_Hasil, $saldoTerakhirKendaraan_Jumlah, 
        $saldoTerakhirLainnya_Wakif, $saldoTerakhirLainnya_Hasil, $saldoTerakhirLainnya_Jumlah, 
        $saldoTerakhirHKI_Wakif, $saldoTerakhirHKI_Hasil, $saldoTerakhirHKI_Jumlah, 
        $saldoTerakhirAsetTidakLancarLain_Wakif, $saldoTerakhirAsetTidakLancarLain_Hasil, $saldoTerakhirAsetTidakLancarLain_Jumlah, 
        $jumlah_aset_wakif, $jumlah_aset_hasil, $jumlah_aset];


        return $array;
    }
        
    public function LaporanRincianAsetWakafx1()
    {
        $year = Carbon::now()->format('Y');

        //KAS DAN SETARA KAS
            //WAKIF
            //tunai
            $sumKasTunaiWakif = KasTunai::whereYear('created_at', '=', $year-1)->where('data_wakif_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaranTunaiWakif = KasTunai::whereYear('created_at', '=', $year-1)->where('data_wakif_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhirKasTunai_Wakif =$sumKasTunaiWakif - $sumPengeluaranTunaiWakif;

            //HASIL PENGELOLAAN
            //tunai
            $sumKasTunai = KasTunai::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaranTunai = KasTunai::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhirKasTunai = $sumKasTunai - $sumPengeluaranTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktw = KasTabWakaf::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumPengeluaran_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumPengeluaran_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbnh = KasTabNonBagiHasil::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumPengeluaran_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kdw = KasDepositoWakaf::whereYear('created_at', '=', $year-1)->where('pengelolaan_id','!=','0')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumPengeluaran_kdw;

            $jumlah_wakif_kas = $saldoTerakhirKasTunai_Wakif;
            $jumlah_hasil_pengelolaan_kas = $saldoTerakhirKasTunai + $saldoTerakhir_ktw + $saldoTerakhir_ktbh + $saldoTerakhir_ktbnh + $saldoTerakhir_kdw;
            $jumlah_kas = $jumlah_wakif_kas + $jumlah_hasil_pengelolaan_kas;

        //PIUTANG
        //HASIL PENGELOLAAN
            //pjp = piutang jangka pendek
            $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pjp = $sum_pjp - $sumPengeluaran_pjp;

            //pja = piutang jangka panjang
            $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pja = $sum_pja - $sumPengeluaran_pja;

            //hasil pengelolaan dan jumlah piutang
            $jumlah_piutang = $saldoTerakhir_pjp + $saldoTerakhir_pja;

        //BILYET DEPOSITO
        $saldoTerakhirBilyetDeposito_Wakif = 0;
        $saldoTerakhirBilyetDeposito_Hasil = 0;
        $saldoTerakhirBilyetDeposito_Jumlah = $saldoTerakhirBilyetDeposito_Wakif + $saldoTerakhirBilyetDeposito_Hasil;

        //LOGAM MULIA
        $saldoTerakhirLogamMulia_Wakif = 0;
        $saldoTerakhirLogamMulia_Hasil = 0;
        $saldoTerakhirLogamMulia_Jumlah = $saldoTerakhirLogamMulia_Wakif + $saldoTerakhirLogamMulia_Hasil;
        
        //ASET LANCAR LAIN
        $saldoTerakhirAsetLancarLain_Wakif = 0;
        $saldoTerakhirAsetLancarLain_Hasil = 0;
        $saldoTerakhirAsetLancarLain_Jumlah = $saldoTerakhirAsetLancarLain_Wakif + $saldoTerakhirAsetLancarLain_Hasil;

        //HAK SEWA
        $saldoTerakhirHakSewa_Wakif = 0;
        $saldoTerakhirHakSewa_Hasil = 0;
        $saldoTerakhirHakSewa_Jumlah = $saldoTerakhirHakSewa_Wakif + $saldoTerakhirHakSewa_Hasil;
        
        //INVESTASI PADA ENTITAS LAIN
        $saldoTerakhirInvestasiEntitasLain_Wakif = 0;
        $saldoTerakhirInvestasiEntitasLain_Hasil = 0;
        $saldoTerakhirInvestasiEntitasLain_Jumlah = $saldoTerakhirInvestasiEntitasLain_Wakif + $saldoTerakhirInvestasiEntitasLain_Hasil;

        //ASET TETAP
        $saldoTerakhirTanah_Wakif = 0;
        $saldoTerakhirTanah_Hasil = 0;
        $saldoTerakhirTanah_Jumlah = $saldoTerakhirTanah_Wakif + $saldoTerakhirTanah_Hasil;

        $saldoTerakhirBangunan_Wakif = 0;
        $saldoTerakhirBangunan_Hasil = 0;
        $saldoTerakhirBangunan_Jumlah = $saldoTerakhirBangunan_Wakif + $saldoTerakhirBangunan_Hasil;

        $saldoTerakhirRumah_Wakif = 0;
        $saldoTerakhirRumah_Hasil = 0;
        $saldoTerakhirRumah_Jumlah = $saldoTerakhirRumah_Wakif + $saldoTerakhirRumah_Hasil;

        $saldoTerakhirKendaraan_Wakif = 0;
        $saldoTerakhirKendaraan_Hasil = 0;
        $saldoTerakhirKendaraan_Jumlah = $saldoTerakhirKendaraan_Wakif + $saldoTerakhirKendaraan_Hasil;

        $saldoTerakhirLainnya_Wakif = 0;
        $saldoTerakhirLainnya_Hasil = 0;
        $saldoTerakhirLainnya_Jumlah = $saldoTerakhirLainnya_Wakif + $saldoTerakhirLainnya_Hasil;

        $saldoTerakhirHKI_Wakif = 0;
        $saldoTerakhirHKI_Hasil = 0;
        $saldoTerakhirHKI_Jumlah = $saldoTerakhirHKI_Wakif + $saldoTerakhirHKI_Hasil;

        $saldoTerakhirAsetTidakLancarLain_Wakif = 0;
        $saldoTerakhirAsetTidakLancarLain_Hasil = 0;
        $saldoTerakhirAsetTidakLancarLain_Jumlah = $saldoTerakhirAsetTidakLancarLain_Wakif + $saldoTerakhirAsetTidakLancarLain_Hasil;

            /* //TANAH
            $sum_tanah = AkunTanah::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_tanah = AkunTanah::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_tanah = $sum_tanah - $sumPengeluaran_tanah;

            //BANGUNAN
            $sum_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_gedung = AkunGedungdanBangunan::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_gedung = $sum_gedung - $sumPengeluaran_gedung;

            //KENDARAAN
            $kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_kendaraan = AkunMesindanKendaraan::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kendaraan = $sum_kendaraan - $sumPengeluaran_kendaraan;

            //LAINNYA
            $aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_aset_lain = AkunAsetLainLain::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_aset_lain = $sumPengeluaran_aset_lain - $sumPengeluaran_aset_lain; 

            $saldo_terakhir_aset_tetap =  $saldoTerakhir_kendaraan + $saldoTerakhir_gedung + $saldoTerakhir_tanah + $saldoTerakhir_aset_lain; */

        //Jumlah aset

        $jumlah_aset_wakif = $saldoTerakhirKasTunai_Wakif + $saldoTerakhirBilyetDeposito_Wakif + $saldoTerakhirLogamMulia_Wakif 
        + $saldoTerakhirAsetLancarLain_Wakif + $saldoTerakhirHakSewa_Wakif + 
        $saldoTerakhirInvestasiEntitasLain_Wakif + $saldoTerakhirTanah_Wakif + $saldoTerakhirBangunan_Wakif + 
        $saldoTerakhirRumah_Wakif + $saldoTerakhirKendaraan_Wakif + $saldoTerakhirLainnya_Wakif 
        + $saldoTerakhirHKI_Wakif + $saldoTerakhirAsetTidakLancarLain_Wakif;

        $jumlah_aset_hasil = $jumlah_hasil_pengelolaan_kas + $jumlah_piutang + $saldoTerakhirBilyetDeposito_Hasil + $saldoTerakhirLogamMulia_Hasil 
        + $saldoTerakhirAsetLancarLain_Hasil + $saldoTerakhirHakSewa_Hasil + 
        $saldoTerakhirInvestasiEntitasLain_Hasil + $saldoTerakhirTanah_Hasil + $saldoTerakhirBangunan_Hasil + 
        $saldoTerakhirRumah_Hasil + $saldoTerakhirKendaraan_Hasil + $saldoTerakhirLainnya_Hasil 
        + $saldoTerakhirHKI_Hasil + $saldoTerakhirAsetTidakLancarLain_Hasil;

        $jumlah_aset = $jumlah_kas + $jumlah_piutang + $saldoTerakhirBilyetDeposito_Jumlah + $saldoTerakhirLogamMulia_Jumlah 
        + $saldoTerakhirAsetLancarLain_Jumlah + $saldoTerakhirHakSewa_Jumlah + 
        $saldoTerakhirInvestasiEntitasLain_Jumlah + $saldoTerakhirTanah_Jumlah + $saldoTerakhirBangunan_Jumlah + 
        $saldoTerakhirRumah_Jumlah + $saldoTerakhirKendaraan_Jumlah + $saldoTerakhirLainnya_Jumlah 
        + $saldoTerakhirHKI_Jumlah + $saldoTerakhirAsetTidakLancarLain_Jumlah;

        $array = [$saldoTerakhirKasTunai_Wakif, $jumlah_hasil_pengelolaan_kas, $jumlah_kas, $jumlah_piutang, $jumlah_piutang,
        $saldoTerakhirBilyetDeposito_Wakif, $saldoTerakhirBilyetDeposito_Hasil, $saldoTerakhirBilyetDeposito_Jumlah, 
        $saldoTerakhirLogamMulia_Wakif, $saldoTerakhirLogamMulia_Hasil, $saldoTerakhirLogamMulia_Jumlah, 
        $saldoTerakhirAsetLancarLain_Wakif, $saldoTerakhirAsetLancarLain_Hasil, $saldoTerakhirAsetLancarLain_Jumlah, 
        $saldoTerakhirHakSewa_Wakif, $saldoTerakhirHakSewa_Hasil, $saldoTerakhirHakSewa_Jumlah, 
        $saldoTerakhirInvestasiEntitasLain_Wakif, $saldoTerakhirInvestasiEntitasLain_Hasil, $saldoTerakhirInvestasiEntitasLain_Jumlah, 
        $saldoTerakhirTanah_Wakif, $saldoTerakhirTanah_Hasil, $saldoTerakhirTanah_Jumlah, 
        $saldoTerakhirBangunan_Wakif, $saldoTerakhirBangunan_Hasil, $saldoTerakhirBangunan_Jumlah, 
        $saldoTerakhirRumah_Wakif, $saldoTerakhirRumah_Hasil, $saldoTerakhirRumah_Jumlah, 
        $saldoTerakhirKendaraan_Wakif, $saldoTerakhirKendaraan_Hasil, $saldoTerakhirKendaraan_Jumlah, 
        $saldoTerakhirLainnya_Wakif, $saldoTerakhirLainnya_Hasil, $saldoTerakhirLainnya_Jumlah, 
        $saldoTerakhirHKI_Wakif, $saldoTerakhirHKI_Hasil, $saldoTerakhirHKI_Jumlah, 
        $saldoTerakhirAsetTidakLancarLain_Wakif, $saldoTerakhirAsetTidakLancarLain_Hasil, $saldoTerakhirAsetTidakLancarLain_Jumlah, 
        $jumlah_aset_wakif, $jumlah_aset_hasil, $jumlah_aset];


        return $array;
    }
}