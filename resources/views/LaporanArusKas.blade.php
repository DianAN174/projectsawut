<!DOCTYPE html>
<html>
<title>Laporan Arus Kas</title>
<body>
<style>
.page-break {
    page-break-after: always;
}
</style>

<h1>Laporan Arus Kas<br>
BWUT MUI DIY<br>
Per 31 Desember {{ $year2 }}</h1>

<table style="width:100%">
  <tr>
    <th></th>
    <th>31 Desember {{ $year2 }}</th>
  </tr>
  <tr>
    <td><h2>AKTIVITAS OPERASI<h2></td>
    <td></td>
  </tr>
  <tr>
    <td><h3>PENERIMAAN<h3></td>
    <td></td>
  </tr>
  <tr>
    <td>Penerimaan Wakaf Uang Tunai Permanen</td>
    <td>{{ $permanen2 }}</td>
  </tr>
  <tr>
    <td>Penerimaan Wakaf Uang Tunai Temporer Jangka Pendek</td>
    <td>{{ $wtjp2 }}</td>
  </tr>
  <tr>
    <td>Penerimaan Piutang Jangka Pendek</td>
    <td>{{ $pjp2 }}</td>
  </tr>
  <tr>
    <td>Penerimaan Piutang Jangka Panjang</td>
    <td>{{ $pja2 }}</td>
  </tr>
  <tr>
    <td>Penerimaan Bagi Hasil Pengelolaan & Pengembangan Wakaf</td>
    <td>{{ $penerimaan_bagi_hasil2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL PENERIMAAN<h4></td>
    <td> {{ $total_penerimaan_operasi2 }}</td>
  </tr>
  <tr>
    <td><h3>PENGELUARAN<h3></td>
    <td></td>
  </tr>
  <tr>
    <td>Beban Pengelolaan dan Pengembangan Wakaf</td>
    <td>{{ $bpp2 }}</td>
  </tr>
  <tr>
    <td>Bagian Nazhir atas Pengelolaan dan Pengembangan Wakaf</td>
    <td>{{ $bna2 }}</td>
  </tr>
  <tr>
    <td>Beban ATK</td>
    <td>{{ $atk2 }}</td>
  </tr>
  <tr>
    <td>Beban Pemasaran</td>
    <td>{{ $pemasaran2 }}</td>
  </tr>
  <tr>
    <td>Beban Rapat</td>
    <td>{{ $rapat2 }}</td>
  </tr>
  <tr>
    <td>Beban Penyaluran Manfaat Wakaf</td>
    <td>{{ $penyaluran2 }}</td>
  </tr>
  <tr>
    <td>Beban Administrasi Bank</td>
    <td>{{ $administrasi2 }}</td>
  </tr>
  <tr>
    <td>Beban Pajak</td>
    <td>{{ $pajak2 }}</td>
  </tr>
  <tr>
    <td>Utang Biaya</td>
    <td>{{ $utangbiaya2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL PENGELUARAN<h4></td>
    <td>{{ $total_pengeluaran_operasi2 }}</td>
  </tr>
  <tr>
    <td><h3>KAS NETO DARI AKTIVITAS OPERASI<h3></td>
    <td>{{ $kas_neto_operasi2 }}</td>
  </tr>
  <tr>
    <td><h2><div class="page-break">AKTIVITAS INVESTASI<h2></td>
    <td></td>
  </tr>
  <tr>
    <td><h3>PENERIMAAN<h3></td>
    <td></td>
  </tr>
  <tr>
    <td>Penjualan Aktiva Tetap</td>
    <td>{{ $penjualanaktivatetap2 }}</td>
  </tr>
  <tr>
    <td><h3>PENGELUARAN<h3></td>
    <td></td>
  </tr>
  <tr>
    <td>Perolehan Aktiva Tetap</td>
    <td>{{ $perolehanaktivatetap2 }}</td>
  </tr>
  <tr>
    <td><h3>KAS NETO DARI AKTIVITAS INVESTASI<h3></td>
    <td>{{ $kas_neto_investasi2 }}</td>
  </tr>
  <tr>
    <td><h2>AKTIVITAS PENDANAAN<h2></td>
    <td></td>
  </tr>
  <tr>
    <td><h3>PENERIMAAN<h3></td>
    <td></td>
  </tr>
  <tr>
    <td>Utang Jangka Panjang</td>
    <td>{{ $uja2 }}</td>
  </tr>
  <tr>
    <td>Penerimaan Wakaf Uang Tunai Temporer Jangka Panjang</td>
    <td>{{ $wtja2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL PENERIMAAN<h4></td>
    <td>{{ $total_penerimaan_pendanaan2 }}</td>
  </tr>
  <tr>
    <td><h3>PENGELUARAN<h3></td>
    <td></td>
  </tr>
  <tr>
    <td>Pembayaran Utang Jangka Panjang</td>
    <td>{{ $pembayaranuja2 }}</td>
  </tr>
  <tr>
    <td>Pengembalian Wakaf Uang Tunai Temporer Jangka Panjang</td>
    <td>{{ $pengembalianwtja2 }}</td>
  </tr>
  <tr>
    <td><h4>TOTAL PENGELUARAN<h4></td>
    <td>{{ $total_pengeluaran_pendanaan2 }}</td>
  </tr>
  <tr>
    <td><h3>KAS NETO DARI AKTIVITAS PENDANAAN<h3></td>
    <td>{{ $kas_neto_pendanaan2 }}</td>
  </tr>
  <tr>
    <td><h2><div class="page-break">KENAIKAN/(PENURUNAN) NETO KAS<h2></td>
    <td>{{ $kenaikan_penurunan_neto_kas2 }}</td>
  </tr>
  <tr>
    <td><h2>KAS AWAL PERIODE<h2></td>
    <td>{{ $kas_awal_periode2 }}</td>
  </tr>
  <tr>
    <td><h2>KAS AKHIR PERIODE<h2></td>
    <td>{{ $kas_akhir_periode2 }}</td>
  </tr>
</table>

</body>
</html>
