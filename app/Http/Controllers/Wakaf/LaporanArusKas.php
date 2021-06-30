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


Class LaporanArusKas
{
    public function Generate()
    {
        $year = Carbon::now()->format('Y');
        $periode_lalu = $this->LaporanArusKasx1();
        $periode_ini = $this->LaporanArusKasx2();
        $data = [
            //'year1' => (string) $year1,
            'year2' => (string) $year,
            //'permanen1' => (string) $periode_lalu[0],
            'permanen2' => (string) $periode_ini[0],
            //'wtjp1' => (string) $periode_lalu[1],
            'wtjp2' => (string) $periode_ini[1],
            //'pjp1' => (string) $periode_lalu[2],
            'pjp2' => (string) $periode_ini[2],
            //'pja1' => (string) $periode_lalu[3],
            'pja2' => (string) $periode_ini[3],
            //'penerimaan_bagi_hasil1' => (string) $periode_lalu[4],
            'penerimaan_bagi_hasil2' => (string) $periode_ini[4],
            //'total_penerimaan_operasi1' => (string) $periode_lalu[5],
            'total_penerimaan_operasi2' => (string) $periode_ini[5],
            //'bpp1' => (string) $periode_lalu[6],
            'bpp2' => (string) $periode_ini[6],
            //'bna1' => (string) $periode_lalu[7],
            'bna2' => (string) $periode_ini[7],
            //'atk1' => (string) $periode_lalu[8],
            'atk2' => (string) $periode_ini[8],
            //'pemasaran1' => (string) $periode_lalu[9],
            'pemasaran2' => (string) $periode_ini[9],
            //'rapat1' => (string) $periode_lalu[10],
            'rapat2' => (string) $periode_ini[10],
            //'penyaluran1' => (string) $periode_lalu[11],
            'penyaluran2' => (string) $periode_ini[11],
            //'administrasi1' => (string) $periode_lalu[12],
            'administrasi2' => (string) $periode_ini[12],
            //'pajak1' => (string) $periode_lalu[13],
            'pajak2' => (string) $periode_ini[13],
            //'utangbiaya1' => (string) $periode_lalu[14],
            'utangbiaya2' => (string) $periode_ini[14],
            //'total_pengeluaran_operasi1' => (string) $periode_lalu[15],
            'total_pengeluaran_operasi2' => (string) $periode_ini[15],
            //'kas_neto_operasi1' => (string) $periode_lalu[16],
            'kas_neto_operasi2' => (string) $periode_ini[16],
            //'penjualanaktivatetap1' => (string) $periode_lalu[17],
            'penjualanaktivatetap2' => (string) $periode_ini[17],
            //'perolehanaktivatetap1' => (string) $periode_lalu[18],
            'perolehanaktivatetap2' => (string) $periode_ini[18],
            //'kas_neto_investasi1' => (string) $periode_lalu[19],
            'kas_neto_investasi2' => (string) $periode_ini[19],
            //'uja1' => (string) $periode_lalu[20],
            'uja2' => (string) $periode_ini[20],
            //'wtja1' => (string) $periode_lalu[21],
            'wtja2' => (string) $periode_ini[21],
            //'total_penerimaan_pendanaan1' => (string) $periode_lalu[22],
            'total_penerimaan_pendanaan2' => (string) $periode_ini[22],
            //'pembayaranuja1' => (string) $periode_lalu[23],
            'pembayaranuja2' => (string) $periode_ini[23],
            //'pengembalianwtja1' => (string) $periode_lalu[24],
            'pengembalianwtja2' => (string) $periode_ini[24],
            //'total_pengeluaran_pendanaan1' => (string) $periode_lalu[25],
            'total_pengeluaran_pendanaan2' => (string) $periode_ini[25],
            //'kas_neto_pendanaan1' => (string) $periode_lalu[26],
            'kas_neto_pendanaan2' => (string) $periode_ini[26],
            //'kenaikan_penurunan_neto_kas1' => (string) $periode_lalu[27],
            'kenaikan_penurunan_neto_kas2' => (string) $periode_ini[27],
            //'kas_awal_periode1' => (string) $periode_lalu[28],
            'kas_awal_periode2' => (string) $periode_ini[28],
            //'kas_akhir_periode1' => (string) $periode_lalu[29],
            'kas_akhir_periode2' => (string) $periode_ini[29],
        ];
        $pdf = PDF::loadView('LaporanArusKas', $data);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('LaporanArusKas.pdf',array("Attachment"=>0));
    }

    public function Download()
    {
        $year = Carbon::now()->format('Y');
        $periode_ini = $this->LaporanArusKasx2();
        $data = [
            //'year1' => (string) $year1,
            'year2' => (string) $year,
            //'permanen1' => (string) $periode_lalu[0],
            'permanen2' => (string) $periode_ini[0],
            //'wtjp1' => (string) $periode_lalu[1],
            'wtjp2' => (string) $periode_ini[1],
            //'pjp1' => (string) $periode_lalu[2],
            'pjp2' => (string) $periode_ini[2],
            //'pja1' => (string) $periode_lalu[3],
            'pja2' => (string) $periode_ini[3],
            //'penerimaan_bagi_hasil1' => (string) $periode_lalu[4],
            'penerimaan_bagi_hasil2' => (string) $periode_ini[4],
            //'total_penerimaan_operasi1' => (string) $periode_lalu[5],
            'total_penerimaan_operasi2' => (string) $periode_ini[5],
            //'bpp1' => (string) $periode_lalu[6],
            'bpp2' => (string) $periode_ini[6],
            //'bna1' => (string) $periode_lalu[7],
            'bna2' => (string) $periode_ini[7],
            //'atk1' => (string) $periode_lalu[8],
            'atk2' => (string) $periode_ini[8],
            //'pemasaran1' => (string) $periode_lalu[9],
            'pemasaran2' => (string) $periode_ini[9],
            //'rapat1' => (string) $periode_lalu[10],
            'rapat2' => (string) $periode_ini[10],
            //'penyaluran1' => (string) $periode_lalu[11],
            'penyaluran2' => (string) $periode_ini[11],
            //'administrasi1' => (string) $periode_lalu[12],
            'administrasi2' => (string) $periode_ini[12],
            //'pajak1' => (string) $periode_lalu[13],
            'pajak2' => (string) $periode_ini[13],
            //'utangbiaya1' => (string) $periode_lalu[14],
            'utangbiaya2' => (string) $periode_ini[14],
            //'total_pengeluaran_operasi1' => (string) $periode_lalu[15],
            'total_pengeluaran_operasi2' => (string) $periode_ini[15],
            //'kas_neto_operasi1' => (string) $periode_lalu[16],
            'kas_neto_operasi2' => (string) $periode_ini[16],
            //'penjualanaktivatetap1' => (string) $periode_lalu[17],
            'penjualanaktivatetap2' => (string) $periode_ini[17],
            //'perolehanaktivatetap1' => (string) $periode_lalu[18],
            'perolehanaktivatetap2' => (string) $periode_ini[18],
            //'kas_neto_investasi1' => (string) $periode_lalu[19],
            'kas_neto_investasi2' => (string) $periode_ini[19],
            //'uja1' => (string) $periode_lalu[20],
            'uja2' => (string) $periode_ini[20],
            //'wtja1' => (string) $periode_lalu[21],
            'wtja2' => (string) $periode_ini[21],
            //'total_penerimaan_pendanaan1' => (string) $periode_lalu[22],
            'total_penerimaan_pendanaan2' => (string) $periode_ini[22],
            //'pembayaranuja1' => (string) $periode_lalu[23],
            'pembayaranuja2' => (string) $periode_ini[23],
            //'pengembalianwtja1' => (string) $periode_lalu[24],
            'pengembalianwtja2' => (string) $periode_ini[24],
            //'total_pengeluaran_pendanaan1' => (string) $periode_lalu[25],
            'total_pengeluaran_pendanaan2' => (string) $periode_ini[25],
            //'kas_neto_pendanaan1' => (string) $periode_lalu[26],
            'kas_neto_pendanaan2' => (string) $periode_ini[26],
            //'kenaikan_penurunan_neto_kas1' => (string) $periode_lalu[27],
            'kenaikan_penurunan_neto_kas2' => (string) $periode_ini[27],
            //'kas_awal_periode1' => (string) $periode_lalu[28],
            'kas_awal_periode2' => (string) $periode_ini[28],
            //'kas_akhir_periode1' => (string) $periode_lalu[29],
            'kas_akhir_periode2' => (string) $periode_ini[29],
        ];
        $pdf = PDF::loadView('LaporanArusKas', $data);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->download('LaporanArusKas.pdf');
    }

    public function LaporanArusKasx2()
    {
        $year = Carbon::now()->format('Y');

        //AKTIVITAS OPERASI
        //Penerimaan Wakaf Uang Tunai Permanen
        $sum_permanen = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_permanen  = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_permanen = $sum_permanen - $sumPengeluaran_permanen;

        //Penerimaan Wakaf Uang Tunai Temporer Jangka Pendek
        
        $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_wtjp = $sum_wtjp - $sumPengeluaran_wtjp;

        //Penerimaan Piutang Jangka Pendek
        //pjp = piutang jangka pendek
        $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_pjp = $sum_pjp - $sumPengeluaran_pjp;

        //Penerimaan Piutang Jangka Panjang
        //pja = piutang jangka panjang
        $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_pja = $sum_pja - $sumPengeluaran_pja;

        //Penerimaan Bagi Hasil Pengelolaan dan Pengembangan Wakaf
        $saldoTerakhir_penerimaan_bagihasil = 0;
        //TOTAL PENERIMAAN
        $total_penerimaan_operasi = $saldoTerakhir_permanen + $saldoTerakhir_wtjp + $saldoTerakhir_pjp + $saldoTerakhir_pja;

        //PENGELUARAN
        //Beban Pengelolaan dan Pengembangan Wakaf
        //bpp = beban pengelolaan dan pengembangan wakaf
        $sum_bpp = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_bpp  = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_bpp = $sum_bpp - $sumPengeluaran_bpp;

        //Bagian Nazhir atas Pengelolaan dan Pengembangan Wakaf
        //bnp = beban nazhir atas pengelolaan dan pengembangan wakaf
        $sum_bnp = BagianNazhir::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_bnp  = BagianNazhir::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_bnp = $sum_bnp - $sumPengeluaran_bnp;

        //Beban ATK
        $sum_atk = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','atk')->sum('nominal');
        //    $sumPengeluaran_atk  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','atk')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_atk = $sum_atk;
        //Beban Pemasaran
        $sum_pemasaran = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','pemasaran')->sum('nominal');
        //    $sumPengeluaran_pemasaran  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','pemasaran')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_pemasaran = $sum_pemasaran;
        //Beban Rapat
        $sum_rapat = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','rapat')->sum('nominal');
        //    $sumPengeluaran_rapat  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','rapat')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_rapat = $sum_rapat;
        //Beban Penyaluran Manfaat Wakaf
        $sum_penyaluran = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','penyaluran')->sum('nominal');
        //    $sumPengeluaran_penyaluran  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','penyaluran')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_penyaluran = $sum_penyaluran;
        //Beban Administrasi Bank
        $sum_administrasi = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','administrasi')->sum('nominal');
        //    $sumPengeluaran_administrasi  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','administrasi')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_administrasi = $sum_administrasi;
        //Beban Pajak
        $sum_pajak = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','pajak')->sum('nominal');
        //    $sumPengeluaran_pajak  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','pajak')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_pajak = $sum_pajak;
        //Utang Biaya
        $biaya = UtangBiaya::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_biaya = UtangBiaya::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_biaya = $sumPengeluaran_biaya - $sumPengeluaran_biaya;

        //TOTAL PENGELUARAN
        $total_pengeluaran_operasi = $saldoTerakhir_atk + $saldoTerakhir_pemasaran + $saldoTerakhir_rapat +$saldoTerakhir_penyaluran + $saldoTerakhir_administrasi + $saldoTerakhir_pajak + $saldoTerakhir_biaya;
        //KAS NETO DARI AKTIVITAS OPERASI
        $kas_neto_operasi = $total_penerimaan_operasi - $total_pengeluaran_operasi;

        //AKTIVITAS INVESTASI
        //PENERIMAAN
        //Penjualan Aktiva Tetap
        $saldoTerakhir_penjualanAktivaTetap=0;

        //PENGELUARAN
        //Perolehan aktiva tetap
            $sum_AsetTetap = DataAsetTetap::whereYear('created_at', '=', ($year))->sum('harga_perolehan');
            //$sumPengeluaran_AsetTetap = DataAsetTetap::whereYear('created_at', '=', ($year))->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_AsetTetap = $sum_AsetTetap;
        
        //KAS NETO DARI AKTIVITAS INVESTASI
        $kas_neto_investasi = 0;

        //AKTIVITAS PENDANAAN
        //Utang Jangka Panjang
        $sum_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_uja = $sum_uja - $sumPengeluaran_pja;
        //Penerimaan Wakaf Uang Tunai Temporer Jangka Panjang
        $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumPengeluaran_wtja;
        //TOTAL PENERIMAAN
        $total_penerimaan_pendanaan = $saldoTerakhir_uja + $saldoTerakhir_pja;

        //PENGELUARAN
        //Pembayaran Utang Jangka Panjang
        $saldoTerakhir_pembayaranUtangJP=0;
        //Pengembalian Wakaf Uang Tunai Temporer Jangka Panjang
        $saldoTerakhir_pengembalianWakafTemporerJP=0;

        //TOTAL PENGELUARAN
        $total_pengeluaran_pendanaan = 0;

        //KAS NETO DARI AKTIVITAS PENDANAAN
        $kas_neto_pendanaan = $total_penerimaan_pendanaan - $total_pengeluaran_pendanaan;

        //KENAIKAN /PENURUNAN NETO KAS
        $kenaikan_penurunan_neto_kas = 0;

        //KAS AWAL PERIODE
        $kas_awal_periode = 0;
        
        //KAS AKHIR PERIODE
        $kas_akhir_periode = 0;

        $array=[$saldoTerakhir_permanen,$saldoTerakhir_wtjp,
        $saldoTerakhir_pjp,$saldoTerakhir_pja, $saldoTerakhir_penerimaan_bagihasil,
        $total_penerimaan_operasi,
        $saldoTerakhir_bpp,$saldoTerakhir_bnp,
        $saldoTerakhir_atk,$saldoTerakhir_pemasaran,
        $saldoTerakhir_rapat,$saldoTerakhir_penyaluran,
        $saldoTerakhir_administrasi,$saldoTerakhir_pajak,
        $saldoTerakhir_biaya,
        $total_pengeluaran_operasi,
        $kas_neto_operasi,
        $saldoTerakhir_penjualanAktivaTetap,
        $saldoTerakhir_AsetTetap, $kas_neto_investasi,
        $saldoTerakhir_uja, $saldoTerakhir_wtja,
        $total_penerimaan_pendanaan,
        $saldoTerakhir_pembayaranUtangJP,
        $saldoTerakhir_pengembalianWakafTemporerJP,
        $total_pengeluaran_pendanaan,
        $kas_neto_pendanaan, 
        $kenaikan_penurunan_neto_kas,
        $kas_awal_periode, $kas_akhir_periode];

        return $array;
    }

    public function LaporanArusKasx1()
    {
        $year = Carbon::now()->format('Y');

        //AKTIVITAS OPERASI
        //Penerimaan Wakaf Uang Tunai Permanen
        $sum_permanen = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_permanen  = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_permanen = $sum_permanen - $sumPengeluaran_permanen;

        //Penerimaan Wakaf Uang Tunai Temporer Jangka Pendek
        
        $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_wtjp = $sum_wtjp - $sumPengeluaran_wtjp;

        //Penerimaan Piutang Jangka Pendek
        //pjp = piutang jangka pendek
        $sum_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_pjp = PiutangJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_pjp = $sum_pjp - $sumPengeluaran_pjp;

        //Penerimaan Piutang Jangka Panjang
        //pja = piutang jangka panjang
        $sum_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_pja = PiutangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_pja = $sum_pja - $sumPengeluaran_pja;

        //Penerimaan Bagi Hasil Pengelolaan dan Pengembangan Wakaf
        $saldoTerakhir_penerimaan_bagihasil = 0;

        //TOTAL PENERIMAAN
        $total_penerimaan_operasi = $saldoTerakhir_permanen + $saldoTerakhir_wtjp + $saldoTerakhir_pjp + $saldoTerakhir_pja;

        //PENGELUARAN
        //Beban Pengelolaan dan Pengembangan Wakaf
        //bpp = beban pengelolaan dan pengembangan wakaf
        $sum_bpp = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_bpp  = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_bpp = $sum_bpp - $sumPengeluaran_bpp;

        //Bagian Nazhir atas Pengelolaan dan Pengembangan Wakaf
        //bnp = beban nazhir atas pengelolaan dan pengembangan wakaf
        $sum_bnp = BagianNazhir::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
        $sumPengeluaran_bnp  = BagianNazhir::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
        $saldoTerakhir_bnp = $sum_bnp - $sumPengeluaran_bnp;

        //Beban ATK
        $sum_atk = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','atk')->sum('nominal');
        //    $sumPengeluaran_atk  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','atk')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_atk = $sum_atk;
        //Beban Pemasaran
        $sum_pemasaran = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','pemasaran')->sum('nominal');
        //    $sumPengeluaran_pemasaran  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','pemasaran')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_pemasaran = $sum_pemasaran;
        //Beban Rapat
        $sum_rapat = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','rapat')->sum('nominal');
        //    $sumPengeluaran_rapat  = PengajuanBiaya::whereYear('created_at', 'year-1)->where('kategori_biaya','=','rapat')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_rapat = $sum_rapat;
        //Beban Penyaluran Manfaat Wakaf
        $sum_penyaluran = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','penyaluran')->sum('nominal');
        //    $sumPengeluaran_penyaluran  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','penyaluran')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_penyaluran = $sum_penyaluran;
        //Beban Administrasi Bank
        $sum_administrasi = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','administrasi')->sum('nominal');
        //    $sumPengeluaran_administrasi  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','administrasi')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_administrasi = $sum_administrasi;
        //Beban Pajak
        $sum_pajak = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','pajak')->sum('nominal');
        //    $sumPengeluaran_pajak  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','pajak')->where('type','=','pengeluaran')->sum('nominal');
            $saldoTerakhir_pajak = $sum_pajak;
        //Utang Biaya
        $biaya = UtangBiaya::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_biaya = UtangBiaya::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_biaya = $sumPengeluaran_biaya - $sumPengeluaran_biaya;

        //TOTAL PENGELUARAN
        $total_pengeluaran_operasi = $saldoTerakhir_atk + $saldoTerakhir_pemasaran + $saldoTerakhir_rapat +$saldoTerakhir_penyaluran + $saldoTerakhir_administrasi + $saldoTerakhir_pajak + $saldoTerakhir_biaya;
        //KAS NETO DARI AKTIVITAS OPERASI
        $kas_neto_operasi = $total_penerimaan_operasi - $total_pengeluaran_operasi;

        //AKTIVITAS INVESTASI
        //PENERIMAAN
        //Penjualan Aktiva Tetap
        $saldoTerakhir_penjualanAktivaTetap=0;

        //PENGELUARAN
        //Perolehan aktiva tetap
            $sum_AsetTetap = DataAsetTetap::whereYear('created_at', '=', ($year-1))->sum('harga_perolehan');
            //$sumPengeluaran_AsetTetap = DataAsetTetap::whereYear('created_at', '=', ($year-1))->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_AsetTetap = $sum_AsetTetap;
        
        //KAS NETO DARI AKTIVITAS INVESTASI
        $kas_neto_investasi = 0;

        //AKTIVITAS PENDANAAN
        //Utang Jangka Panjang
        $sum_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_uja = UtangJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_uja = $sum_uja - $sumPengeluaran_pja;
        //Penerimaan Wakaf Uang Tunai Temporer Jangka Panjang
        $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumPengeluaran_wtja;

        //TOTAL PENERIMAAN
        $total_penerimaan_pendanaan = $saldoTerakhir_uja + $saldoTerakhir_pja;

        //PENGELUARAN
        //Pembayaran Utang Jangka Panjang
        $saldoTerakhir_pembayaranUtangJP=0;
        //Pengembalian Wakaf Uang Tunai Temporer Jangka Panjang
        $saldoTerakhir_pengembalianWakafTemporerJP=0;

        //TOTAL PENGELUARAN
        $total_pengeluaran_pendanaan = 0;

        //KAS NETO DARI AKTIVITAS PENDANAAN
        $kas_neto_pendanaan = $total_penerimaan_pendanaan - $total_pengeluaran_pendanaan;

        //KENAIKAN /PENURUNAN NETO KAS
        $kenaikan_penurunan_neto_kas = 0;

        //KAS AWAL PERIODE
        $kas_awal_periode = 0;
        
        //KAS AKHIR PERIODE
        $kas_akhir_periode = 0;

        $array=[$saldoTerakhir_permanen,$saldoTerakhir_wtjp,
        $saldoTerakhir_pjp,$saldoTerakhir_pja, $saldoTerakhir_penerimaan_bagihasil,
        $total_penerimaan_operasi,
        $saldoTerakhir_bpp,$saldoTerakhir_bnp,
        $saldoTerakhir_atk,$saldoTerakhir_pemasaran,
        $saldoTerakhir_rapat,$saldoTerakhir_penyaluran,
        $saldoTerakhir_administrasi,$saldoTerakhir_pajak,
        $saldoTerakhir_biaya,
        $total_pengeluaran_operasi,
        $kas_neto_operasi,
        $saldoTerakhir_penjualanAktivaTetap,
        $saldoTerakhir_AsetTetap, $kas_neto_investasi,
        $saldoTerakhir_uja, $saldoTerakhir_wtja,
        $total_penerimaan_pendanaan,
        $saldoTerakhir_pembayaranUtangJP,
        $saldoTerakhir_pengembalianWakafTemporerJP,
        $total_pengeluaran_pendanaan,
        $kas_neto_pendanaan, 
        $kenaikan_penurunan_neto_kas,
        $kas_awal_periode, $kas_akhir_periode];

        return $array;
    }

    
}