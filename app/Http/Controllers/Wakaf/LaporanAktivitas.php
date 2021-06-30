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


Class LaporanAktivitas
{

    public function Generate()
    {
        $year1=2020;
        $year2=2021;
        $periode_lalu = $this->LaporanAktivitasx1();
        $periode_ini = $this->LaporanAktivitasx2();
        $data = [
            'year1' => (string) $year1,
            'year2' => (string) $year2,
            'permanen1' => (string) $periode_lalu[0],
            'permanen2' => (string) $periode_ini[0],
            'temporer1' => (string) $periode_lalu[1],
            'temporer2' => (string) $periode_ini[1],
            'dampak_kenaikan1' =>  (string) $periode_lalu[2],
            'dampak_kenaikan2' =>  (string) $periode_ini[2],
            'total_penerimaan_wakaf1' =>  (string) $periode_lalu[3],
            'total_penerimaan_wakaf2' =>  (string) $periode_ini[3],
            'saldo_awal_tunai1' => (string) $periode_lalu[4],
            'saldo_awal_tunai2' => (string) $periode_ini[4],
            'pengembalian_temporer1' => (string) $periode_lalu[5],
            'pengembalian_temporer2' => (string) $periode_ini[5],
            'dampak_penurunan1' => (string) $periode_lalu[6],
            'dampak_penurunan2' => (string) $periode_ini[6],
            'saldo_akhir_tunai1' => (string) $periode_lalu[7],
            'saldo_akhir_tunai2' => (string) $periode_ini[7],
            'bagi_hasil_deposito1' => (string) $periode_lalu[8],
            'bagi_hasil_deposito2' => (string) $periode_ini[8],
            'bagi_hasil_tabungan1' => (string) $periode_lalu[9],
            'bagi_hasil_tabungan2' => (string) $periode_ini[9],
            'total_manfaat_wakaf1' => (string) $periode_lalu[10],
            'total_manfaat_wakaf2' => (string) $periode_ini[10],
            'bpp1' => (string) $periode_lalu[11],
            'bpp2' => (string) $periode_ini[11],
            'bna1' => (string) $periode_lalu[12],
            'bna2' => (string) $periode_ini[12],
            'total_manfaat_wakaf1' => (string) $periode_lalu[13],
            'total_manfaat_wakaf2' => (string) $periode_ini[13],
            'ekonomi_umat1' => (string) $periode_lalu[14],
            'ekonomi_umat2' => (string) $periode_ini[14],
            'kesejahteraan1' => (string) $periode_lalu[15],
            'kesejahteraan2' => (string) $periode_ini[15],
            'ibadah1' => (string) $periode_lalu[16],
            'ibadah2' => (string) $periode_ini[16],
            'pendidikan1' => (string) $periode_lalu[17],
            'pendidikan2' => (string) $periode_ini[17],
            'kesehatan1' => (string) $periode_lalu[18],
            'kesehatan2' => (string) $periode_ini[18],
            'bantuan1' => (string) $periode_lalu[19],
            'bantuan2' => (string) $periode_ini[19],
            'total_pentasyarufan1' => (string) $periode_lalu[20],
            'total_pentasyarufan2' => (string) $periode_ini[20],
            'kenaikan_penurunan_manfaat1' => (string) $periode_lalu[21],
            'kenaikan_penurunan_manfaat2' => (string) $periode_ini[21],
            'saldo_awal_manfaat1' => (string) $periode_lalu[22],
            'saldo_awal_manfaat2' => (string) $periode_ini[22],
            'saldo_akhir_manfaat1' => (string) $periode_lalu[23],
            'saldo_akhir_manfaat2' => (string) $periode_ini[23]
        ];

        $headers = ['Content-Type' => 'application/pdf'];

        $pdf = PDF::loadView('LaporanAktivitas', $data);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('LaporanAktivitas.pdf',array("Attachment"=>0, $headers));
    }

    public function Download()
    {
        $year1=2020;
        $year2=2021;
        $periode_lalu = $this->LaporanAktivitasx1();
        $periode_ini = $this->LaporanAktivitasx2();
        $data = [
            'year1' => (string) $year1,
            'year2' => (string) $year2,
            'permanen1' => (string) $periode_lalu[0],
            'permanen2' => (string) $periode_ini[0],
            'temporer1' => (string) $periode_lalu[1],
            'temporer2' => (string) $periode_ini[1],
            'dampak_kenaikan1' =>  (string) $periode_lalu[2],
            'dampak_kenaikan2' =>  (string) $periode_ini[2],
            'total_penerimaan_wakaf1' =>  (string) $periode_lalu[3],
            'total_penerimaan_wakaf2' =>  (string) $periode_ini[3],
            'saldo_awal_tunai1' => (string) $periode_lalu[4],
            'saldo_awal_tunai2' => (string) $periode_ini[4],
            'pengembalian_temporer1' => (string) $periode_lalu[5],
            'pengembalian_temporer2' => (string) $periode_ini[5],
            'dampak_penurunan1' => (string) $periode_lalu[6],
            'dampak_penurunan2' => (string) $periode_ini[6],
            'saldo_akhir_tunai1' => (string) $periode_lalu[7],
            'saldo_akhir_tunai2' => (string) $periode_ini[7],
            'bagi_hasil_deposito1' => (string) $periode_lalu[8],
            'bagi_hasil_deposito2' => (string) $periode_ini[8],
            'bagi_hasil_tabungan1' => (string) $periode_lalu[9],
            'bagi_hasil_tabungan2' => (string) $periode_ini[9],
            'total_manfaat_wakaf1' => (string) $periode_lalu[10],
            'total_manfaat_wakaf2' => (string) $periode_ini[10],
            'bpp1' => (string) $periode_lalu[11],
            'bpp2' => (string) $periode_ini[11],
            'bna1' => (string) $periode_lalu[12],
            'bna2' => (string) $periode_ini[12],
            'total_manfaat_wakaf1' => (string) $periode_lalu[13],
            'total_manfaat_wakaf2' => (string) $periode_ini[13],
            'ekonomi_umat1' => (string) $periode_lalu[14],
            'ekonomi_umat2' => (string) $periode_ini[14],
            'kesejahteraan1' => (string) $periode_lalu[15],
            'kesejahteraan2' => (string) $periode_ini[15],
            'ibadah1' => (string) $periode_lalu[16],
            'ibadah2' => (string) $periode_ini[16],
            'pendidikan1' => (string) $periode_lalu[17],
            'pendidikan2' => (string) $periode_ini[17],
            'kesehatan1' => (string) $periode_lalu[18],
            'kesehatan2' => (string) $periode_ini[18],
            'bantuan1' => (string) $periode_lalu[19],
            'bantuan2' => (string) $periode_ini[19],
            'total_pentasyarufan1' => (string) $periode_lalu[20],
            'total_pentasyarufan2' => (string) $periode_ini[20],
            'kenaikan_penurunan_manfaat1' => (string) $periode_lalu[21],
            'kenaikan_penurunan_manfaat2' => (string) $periode_ini[21],
            'saldo_awal_manfaat1' => (string) $periode_lalu[22],
            'saldo_awal_manfaat2' => (string) $periode_ini[22],
            'saldo_akhir_manfaat1' => (string) $periode_lalu[23],
            'saldo_akhir_manfaat2' => (string) $periode_ini[23]
        ];

        $headers = ['Content-Type' => 'application/pdf'];

        $pdf = PDF::loadView('LaporanAktivitas', $data);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->download('LaporanAktivitas.pdf', $headers);
    }

    public function LaporanAktivitasx2()
    {
        $year = Carbon::now()->format('Y');
        //PENGHASILAN
        //Penerimaan Wakaf Permanen
            
            $sum_permanen = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_permanen  = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_permanen = $sum_permanen - $sumPengeluaran_permanen;

        //Penerimaan Wakaf Temporer

            //wtjp = wakaf temporer jangka pendek
            $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtjp = $sum_wtjp - $sumPengeluaran_wtjp;

            //wtja = wakaf temporer jangka panjang
            $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumPengeluaran_wtja;
        
            $saldoTerakhir_temporer = $saldoTerakhir_wtjp + $saldoTerakhir_wtja;

        //Dampak KenaikanPengukuran Ulang Aset Wakaf Uang Tunai
        $saldoTerakhir_dampakKenaikanPengukuranUlang = 0;
        //TOTAL PENERIMAAN WAKAF
        $total_penerimaan_wakaf = $saldoTerakhir_permanen + $sum_wtjp +$sum_wtja + $saldoTerakhir_dampakKenaikanPengukuranUlang;
        //SALDO AWAL WAKAF UANG TUNAI
        $saldo_awal_wakaf = $total_penerimaan_wakaf;

        //Pengembalian Wakaf Uang Tunai Temporer
        $saldoTerakhir_pengembalianTemporer = $sum_wtjp + $sum_wtja;

        //Dampak Penurunan Pengukuran Ulang Aset Wakaf Uang Tunai
        $saldoTerakhir_dampakPenurunanPengukuranUlang = 0;
        //SALDO AKHIR WAKAF UANG TUNAI
        $saldo_akhir_wakaf = $saldoTerakhir_permanen - $sum_wtjp - $sum_wtja;
        
        //PENGELOLAAN DAN PENGEMBANGAN WAKAF

            //Bagi Hasil Deposito Wakaf Tunai
            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumPengeluaran_ktbh;

            //Bagi Hasil Tabungan Wakaf Tunai

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktw = KasTabWakaf::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumPengeluaran_ktw;

        //TOTAL MANFAAT WAKAF UANG TUNAI
        $total_manfaat = $saldoTerakhir_ktbh + $saldoTerakhir_ktw;

        //PENGELUARAN DAN PENTASYARUFAN MANFAAT WAKAF

            //PENGELUARAN
            //bpp = beban pengelolaan dan pengembangan wakaf
            $sum_bpp = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_bpp  = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_bpp = $sum_bpp - $sumPengeluaran_bpp;

            //bnp = bagian nazhir atas pengelolaan dan pengembangan wakaf
            $sum_bna = BagianNazhir::whereYear('created_at', '=', $year)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_bna  = BagianNazhir::whereYear('created_at', '=', $year)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_bna = $sum_bna - $sumPengeluaran_bna;

            //TOTAL PENGELUARAN
            $total_pengeluaran = $saldoTerakhir_bpp + $saldoTerakhir_bna;

            //$jumlah_penghasilan = $saldoTerakhir_permanen + $saldoTerakhir_wtjp + $saldoTerakhir_wtja + $saldoTerakhir_bpp + $saldoTerakhir_bnp;

            //PENTASYARUFAN MANFAAT WAKAF

            //HIBAH PRODUKTIF
            //kegiatan ekonomi umat
            $sum_ekonomiUmat = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','ekonomiUmat')->sum('nominal');
            //$sumPengeluaran_ekonomiUmat  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','ekonomiUmat')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ekonomiUmat = $sum_ekonomiUmat;
            //kegiatan kesejahteraan umat lain
            $sum_kesejahteraan = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','kesejahteraan')->sum('nominal');
            //$sumPengeluaran_kesejahteraan  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','kesejahteraan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kesejahteraan = $sum_kesejahteraan;
        
            //HIBAH KONSUMTIF
            //Kegiatan ibadah
            $sum_ibadah = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','ibadah')->sum('nominal');
            //$sumPengeluaran_ibadah  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','ibadah')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ibadah = $sum_ibadah;


            //kegiatan pendidikan
            $sum_pendidikan = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','pendidikan')->sum('nominal');
            //$sumPengeluaran_pendidikan  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','pendidikan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pendidikan = $sum_pendidikan;

            //Kegiatan kesehatan
            $sum_kesehatan = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','kesehatan')->sum('nominal');
            //$sumPengeluaran_kesehatan  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','kesehatan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kesehatan = $sum_kesehatan;

            //kegiatan bantuan
            $sum_bantuan = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','bantuan')->sum('nominal');
            //$sumPengeluaran_bantuan  = PengajuanBiaya::whereYear('created_at', '=', $year)->where('kategori_biaya','=','bantuan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_bantuan = $sum_bantuan;

            //TOTAL PENTASYARUFAN
            $total_pentasyarufan = $saldoTerakhir_ekonomiUmat + $saldoTerakhir_kesejahteraan + $saldoTerakhir_ibadah + $saldoTerakhir_pendidikan + $saldoTerakhir_kesehatan + $saldoTerakhir_bantuan;
            
        //KENAIKAN (PENURUNAN) ASET NETO
        $kenaikan_penurunan_manfaat = $saldo_awal_wakaf + $saldo_akhir_wakaf + $total_manfaat - $total_pengeluaran - $total_pentasyarufan;
        //ASET NETO AWAL MANFAAT WAKAF
        $aset_neto_awal = $saldo_awal_wakaf + $saldo_akhir_wakaf + $total_manfaat;
        //ASET NETO AKHIR MANFAAT WAKAF
        $aset_neto_akhir = $saldo_awal_wakaf + $saldo_akhir_wakaf + $total_manfaat - $total_pengeluaran - $total_pentasyarufan;

        $array = [$saldoTerakhir_permanen, $saldoTerakhir_temporer,
                $saldoTerakhir_dampakKenaikanPengukuranUlang,
                $total_penerimaan_wakaf, $saldo_awal_wakaf,
                $saldoTerakhir_pengembalianTemporer,
                $saldoTerakhir_dampakPenurunanPengukuranUlang,
                $saldo_akhir_wakaf, $saldoTerakhir_ktbh,
                $saldoTerakhir_ktbh, $total_manfaat,
                $saldoTerakhir_bpp, $saldoTerakhir_bna,
                $total_pengeluaran, $saldoTerakhir_ekonomiUmat,
                $saldoTerakhir_kesejahteraan, $saldoTerakhir_ibadah,
                $saldoTerakhir_pendidikan, $saldoTerakhir_kesehatan,
                $saldoTerakhir_bantuan,
                $total_pentasyarufan, $kenaikan_penurunan_manfaat,
                $aset_neto_awal, $aset_neto_akhir];
        
        return $array;
    }

    public function LaporanAktivitasx1()
    {
        $year = Carbon::now()->format('Y');
        //PENGHASILAN
        //Penerimaan Wakaf Permanen
            
            $sum_permanen = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_permanen  = PenerimaanTunaiPermanen::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_permanen = $sum_permanen - $sumPengeluaran_permanen;

        //Penerimaan Wakaf Temporer

            //wtjp = wakaf temporer jangka pendek
            $sum_wtjp = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtjp  = WakafTemporerJangkaPendek::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtjp = $sum_wtjp - $sumPengeluaran_wtjp;

            //wtja = wakaf temporer jangka panjang
            $sum_wtja = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_wtja  = WakafTemporerJangkaPanjang::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_wtja = $sum_wtja - $sumPengeluaran_wtja;
        
            $saldoTerakhir_temporer = $saldoTerakhir_wtjp + $saldoTerakhir_wtja;

        //Dampak KenaikanPengukuran Ulang Aset Wakaf Uang Tunai
        $saldoTerakhir_dampakKenaikanPengukuranUlang = 0;
        //TOTAL PENERIMAAN WAKAF
        $total_penerimaan_wakaf = $saldoTerakhir_permanen + $sum_wtjp +$sum_wtja + $saldoTerakhir_dampakKenaikanPengukuranUlang;
        //SALDO AWAL WAKAF UANG TUNAI
        $saldo_awal_wakaf = $total_penerimaan_wakaf;

        //Pengembalian Wakaf Uang Tunai Temporer
        $saldoTerakhir_pengembalianTemporer = $sum_wtjp + $sum_wtja;

        //Dampak Penurunan Pengukuran Ulang Aset Wakaf Uang Tunai
        $saldoTerakhir_dampakPenurunanPengukuranUlang = 0;
        //SALDO AKHIR WAKAF UANG TUNAI
        $saldo_akhir_wakaf = $saldoTerakhir_permanen - $sum_wtjp - $sum_wtja;
        
        //PENGELOLAAN DAN PENGEMBANGAN WAKAF

            //Bagi Hasil Deposito Wakaf Tunai
            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktbh = KasTabBagiHasil::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumPengeluaran_ktbh;

            //Bagi Hasil Tabungan Wakaf Tunai

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_ktw = KasTabWakaf::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumPengeluaran_ktw;

        //TOTAL MANFAAT WAKAF UANG TUNAI
        $total_manfaat = $saldoTerakhir_ktbh + $saldoTerakhir_ktw;

        //PENGELUARAN DAN PENTASYARUFAN MANFAAT WAKAF

            //PENGELUARAN
            //bpp = beban pengelolaan dan pengembangan wakaf
            $sum_bpp = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_bpp  = BebanPengelolaandanPengembangan::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_bpp = $sum_bpp - $sumPengeluaran_bpp;

            //bnp = bagian nazhir atas pengelolaan dan pengembangan wakaf
            $sum_bna = BagianNazhir::whereYear('created_at', '=', $year-1)->where('type','=','pemasukan')->sum('saldo');
            $sumPengeluaran_bna  = BagianNazhir::whereYear('created_at', '=', $year-1)->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_bna = $sum_bna - $sumPengeluaran_bna;

            //TOTAL PENGELUARAN
            $total_pengeluaran = $saldoTerakhir_bpp + $saldoTerakhir_bna;

            //$jumlah_penghasilan = $saldoTerakhir_permanen + $saldoTerakhir_wtjp + $saldoTerakhir_wtja + $saldoTerakhir_bpp + $saldoTerakhir_bnp;

            //PENTASYARUFAN MANFAAT WAKAF

            //HIBAH PRODUKTIF
            //kegiatan ekonomi umat
            $sum_ekonomiUmat = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','ekonomiUmat')->sum('nominal');
            //$sumPengeluaran_ekonomiUmat  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','ekonomiUmat')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ekonomiUmat = $sum_ekonomiUmat;
            //kegiatan kesejahteraan umat lain
            $sum_kesejahteraan = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','kesejahteraan')->sum('nominal');
            //$sumPengeluaran_kesejahteraan  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','kesejahteraan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kesejahteraan = $sum_kesejahteraan;
        
            //HIBAH KONSUMTIF
            //Kegiatan ibadah
            $sum_ibadah = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','ibadah')->sum('nominal');
            //$sumPengeluaran_ibadah  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','ibadah')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_ibadah = $sum_ibadah;


            //kegiatan pendidikan
            $sum_pendidikan = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','pendidikan')->sum('nominal');
            //$sumPengeluaran_pendidikan  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','pendidikan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_pendidikan = $sum_pendidikan;

            //Kegiatan kesehatan
            $sum_kesehatan = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','kesehatan')->sum('nominal');
            //$sumPengeluaran_kesehatan  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','kesehatan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_kesehatan = $sum_kesehatan;

            //kegiatan bantuan
            $sum_bantuan = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','bantuan')->sum('nominal');
            //$sumPengeluaran_bantuan  = PengajuanBiaya::whereYear('created_at', '=', $year-1)->where('kategori_biaya','=','bantuan')->where('type','=','pengeluaran')->sum('saldo');
            $saldoTerakhir_bantuan = $sum_bantuan;

            //TOTAL PENTASYARUFAN
            $total_pentasyarufan = $saldoTerakhir_ekonomiUmat + $saldoTerakhir_kesejahteraan + $saldoTerakhir_ibadah + $saldoTerakhir_pendidikan + $saldoTerakhir_kesehatan + $saldoTerakhir_bantuan;
            
        //KENAIKAN (PENURUNAN) ASET NETO
        $kenaikan_penurunan_manfaat = $saldo_awal_wakaf + $saldo_akhir_wakaf + $total_manfaat - $total_pengeluaran - $total_pentasyarufan;
        //ASET NETO AWAL MANFAAT WAKAF
        $aset_neto_awal = $saldo_awal_wakaf + $saldo_akhir_wakaf + $total_manfaat;
        //ASET NETO AKHIR MANFAAT WAKAF
        $aset_neto_akhir = $saldo_awal_wakaf + $saldo_akhir_wakaf + $total_manfaat - $total_pengeluaran - $total_pentasyarufan;

        $array = [$saldoTerakhir_permanen, $saldoTerakhir_temporer,
                $saldoTerakhir_dampakKenaikanPengukuranUlang,
                $total_penerimaan_wakaf, $saldo_awal_wakaf,
                $saldoTerakhir_pengembalianTemporer,
                $saldoTerakhir_dampakPenurunanPengukuranUlang,
                $saldo_akhir_wakaf, $saldoTerakhir_ktbh,
                $saldoTerakhir_ktbh, $total_manfaat,
                $saldoTerakhir_bpp, $saldoTerakhir_bna,
                $total_pengeluaran, $saldoTerakhir_ekonomiUmat,
                $saldoTerakhir_kesejahteraan, $saldoTerakhir_ibadah,
                $saldoTerakhir_pendidikan, $saldoTerakhir_kesehatan,
                $saldoTerakhir_bantuan,
                $total_pentasyarufan, $kenaikan_penurunan_manfaat,
                $aset_neto_awal, $aset_neto_akhir];
        
        return $array;
    }
    
}