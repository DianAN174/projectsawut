<!DOCTYPE html>
<html>
<title>Laporan Aktivitas</title>
<body>
<style>
.page-break {
    page-break-after: always;
}
td {
  height: 80px;
  width: 100px;
  text-align: center;
  vertical-align: middle;
}
</style>

<h1>Laporan Aktivitas<br>
BWUT MUI DIY<br>
Per 31 Desember {{ $year1 }} dan {{ $year2 }}</h1>

<table style="width:100%">
  <tr>
    <th></th>
    <th>{{ $year1 }}</th> 
    <th>{{ $year2 }}</th>
  </tr>
  <tr>
    <td><h2>PENGHASILAN<h2></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Penerimaan Wakaf Uang Tunai Permanen</td>
    <td>{{ $permanen1 }}</td>
    <td>{{ $permanen2 }}</td>
  </tr>
  <tr>
    <td>Penerimaan Wakaf Uang Tunai Temporer</td>
    <td>{{ $temporer1 }}</td>
    <td>{{ $temporer2 }}</td>
  </tr>
  <tr>
    <td>Dampak Kenaikan Pengukuran Ulang Wakaf Uang Tunai</td>
    <td>{{ $dampak_kenaikan1 }}</td>
    <td>{{ $dampak_kenaikan2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL PENERIMAAN WAKAF<h4></td>
    <td>{{ $total_penerimaan_wakaf1 }}</td>
    <td>{{ $total_penerimaan_wakaf2 }}</td>
  </tr>
  <tr>
    <td><h4>SALDO AWAL WAKAF UANG TUNAI<h4></td>
    <td>{{ $saldo_awal_tunai1 }}</td>
    <td>{{ $saldo_awal_tunai2 }}</td>
  </tr>
  <tr>
    <td>Pengembalian Wakaf Uang Tunai Temporer</td>
    <td>{{ $pengembalian_temporer1 }}</td>
    <td>{{ $pengembalian_temporer2 }}</td>
  </tr>
  <tr>
    <td>Dampak Penurunan Pengukuran Ulang Wakaf Uang Tunai</td>
    <td>{{ $dampak_penurunan1 }}</td>
    <td>{{ $dampak_penurunan2 }}</td>
  </tr>
  <tr>
    <td><h4>SALDO AKHIR WAKAF UANG TUNAI<h4></td>
    <td>{{ $saldo_akhir_tunai1 }}</td>
    <td>{{ $saldo_akhir_tunai2 }}</td>
  </tr>
  <tr>
    <td><h2>PENGELOLAAN DAN PENGEMBANGAN WAKAF<h2></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Bagi Hasil Deposito Wakaf Tunai</td>
    <td>{{ $bagi_hasil_deposito1 }}</td>
    <td>{{ $bagi_hasil_deposito2 }}</td>
  </tr>
  <tr>
    <td>Bagi Hasil Tabungan Wakaf Tunai</td>
    <td>{{ $bagi_hasil_tabungan1 }}</td>
    <td>{{ $bagi_hasil_tabungan2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL MANFAAT WAKAF UANG TUNAI<h4></td>
    <td>{{ $total_manfaat_wakaf1 }}</td>
    <td>{{ $total_manfaat_wakaf2 }}</td>
  </tr>
  <tr>
    <td><h2><div class="page-break">PENGELUARAN DAN PENTASYARUFAN MANFAAT WAKAF<h2></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><h3>PENGELUARAN<h3></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Beban Pengelolaan dan Pengembangan Wakaf</td>
    <td>{{ $bpp1 }}</td>
    <td>{{ $bpp2 }}</td>
  </tr>
  <tr>
    <td>Bagian Nazhir atas Pengelolaan dan Pengembangan Wakaf</td>
    <td>{{ $bna1 }}</td>
    <td>{{ $bna2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL PENGELUARAN<h4></td>
    <td>{{ $total_manfaat_wakaf1 }}</td>
    <td>{{ $total_manfaat_wakaf2 }}</td>
  </tr>
  <tr>
    <td><h3>PENTASYARUFAN<h3></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><h4>HIBAH PRODUKTIF<h4></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Kegiatan Ekonomi Umat</td>
    <td>{{ $ekonomi_umat1 }}</td>
    <td>{{ $ekonomi_umat2 }}</td>
  </tr>
  <tr>
    <td>Kegiatan Kesejahteraan Umum</td>
    <td>{{ $kesejahteraan1 }}</td>
    <td>{{ $kesejahteraan2 }}</td>
  </tr>
  <tr>
    <td><h4>HIBAH KONSUMTIF<h4></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Kegiatan Ibadah</td>
    <td>{{ $ibadah1 }}</td>
    <td>{{ $ibadah2 }}</td>
  </tr>
  <tr>
    <td>Kegiatan Pendidikan</td>
    <td>{{ $pendidikan1 }}</td>
    <td>{{ $pendidikan2 }}</td>
  </tr>
  <tr>
    <td>Kegiatan Kesehatan</td>
    <td>{{ $kesehatan1 }}</td>
    <td>{{ $kesehatan2 }}</td>
  </tr>
  <tr>
    <td>Kegiatan Bantuan Fakir, Miskin, Yatim, dan Anak Terlantar</td>
    <td>{{ $bantuan1 }}</td>
    <td>{{ $bantuan2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL PENTASYARUFAN MANFAAT WAKAF<h4></td>
    <td>{{ $total_pentasyarufan1 }}</td>
    <td>{{ $total_pentasyarufan2 }}</td>
  </tr>
  <tr>
    <td><h2>KENAIKAN/(PENURUNAN) MANFAAT WAKAF<h2></td>
    <td>{{ $kenaikan_penurunan_manfaat1 }}</td>
    <td>{{ $kenaikan_penurunan_manfaat2 }}</td>
  </tr>
  <tr>
    <td><h2>SALDO AWAL MANFAAT WAKAF<h2></td>
    <td>{{ $saldo_awal_manfaat1 }}</td>
    <td>{{ $saldo_awal_manfaat2 }}</td>
  </tr>
  <tr>
    <td><h2>SALDO AKHIR MANFAAT WAKAF<h2></td>
    <td>{{ $saldo_akhir_manfaat1 }}</td>
    <td>{{ $saldo_akhir_manfaat2 }}</td>
  </tr>
</table>

</body>
</html>
