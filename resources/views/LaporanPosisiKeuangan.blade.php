<!DOCTYPE html>
<html>
<title>Laporan Posisi Keuangan</title>
<body>
<style>
.page-break {
    page-break-after: always;
}
</style>

<h1>Laporan Posisi Keuangan<br>
BWUT MUI DIY<br>
Per 31 Desember {{ $.year1 }} dan {{ $.year2 }}</h1>

<table style="width:100%">
  <tr>
    <th></th>
    <th>{{ $.year1 }}</th> 
    <th>{{ $.year2 }}</th>
  </tr>
  <tr>
    <td><h2>ASET</hr></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><h3>ASET LANCAR<h3></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Kas Tunai</td>
    <td>{{ $.kastunai1 }}</td>
    <td>{{ $.kastunai }}</td>
  </tr>
  <tr>
    <td>Kas Tabungan Wakaf</td>
    <td>{{ $.kastabwakaf1 }}</td>
    <td>{{ $.kastabwakaf }}</td>
  </tr>
  <tr>
    <td>Kas Tabungan Bagi Hasil</td>
    <td>{{ $.kastabbagihasil1 }}</td>
    <td>{{ $.kastabbagihasil }}</td>
  </tr>
  <tr>
    <td>Kas Tabungan Non Bagi Hasil</td>
    <td>{{ $.kastabnonbagihasil1 }}</td>
    <td>{{ $.kastabnonbagihasil }}</td>
  </tr>
  <tr>
    <td>Kas Deposito</td>
    <td>{{ $.kasdeposito1 }}</td>
    <td>{{ $.kasdeposito }}</td>
  </tr>
  <tr>
    <td>Piutang Jangka Pendek</td>
    <td>{{ $.pjp1 }}</td>
    <td>{{ $.pjp }}</td>
  </tr>
  <tr>
    <td>Piutang Jangka Panjang</td>
    <td>{{ $.pja1 }}</td>
    <td>{{ $.pja }}</td>
  </tr>
  <tr>
    <td>Persediaan</td>
    <td>{{ $.persediaan1 }}</td>
    <td>{{ $.persediaan }}</td>
  </tr>
  <tr>
    <td><h3>ASET TIDAK LANCAR<h3></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><h4>ASET TETAP<h4></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Tanah</td>
    <td>{{ $.tanah1 }}</td>
    <td>{{ $.tanah }}</td>
  </tr>
  <tr>
    <td>Gedung dan Bangunan</td>
    <td>{{ $.gedung1 }}</td>
    <td>{{ $.gedung }}</td>
  </tr>
  <tr>
    <td>Mesin dan Kendaraan</td>
    <td>{{ $.mesin1 }}</td>
    <td>{{ $.mesin }}</td>
  </tr>
  <tr>
    <td>Peralatan dan Perlengkapan Kantor</td>
    <td>{{ $.peralatan1 }}</td>
    <td>{{ $.peralatan }}</td>
  </tr>
  <tr>
    <td>Aset Lain-Lain</td>
    <td>{{ $.asetlain1 }}</td>
    <td>{{ $.asetlain }}</td>
  </tr>
  <tr>
    <td><h4>ASET TIDAK LANCAR LAINNYA<h4></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Hak Sewa</td>
    <td>{{ $.haksewa1 }}</td>
    <td>{{ $.haksewa }}</td>
  </tr>
  <tr>
    <td><h3>TOTAL ASET<h3></td>
    <td>{{ $.totalaset1 }}</td>
    <td>{{ $.totalaset }}</td>
  </tr>
  <tr>
    <td><h3><div class="page-break"></div>LIABILITAS<h3></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><h4>LIABILITAS JANGKA PENDEK<h4></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Wakaf Temporer Jangka Pendek</td>
    <td>{{ $.wtjp1 }}</td>
    <td>{{ $.wtjp }}</td>
  </tr>
  <tr>
    <td>Utang Biaya</td>
    <td>{{ $.utangbiaya1 }}</td>
    <td>{{ $.utangbiaya }}</td>
  </tr>
  <tr>
    <td><h4>LIABILITAS JANGKA PANJANG<h4></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Wakaf Temporer Jangka Panjang</td>
    <td>{{ $.wtja1 }}</td>
    <td>{{ $.wtja }}</td>
  </tr>
  <tr>
    <td>Utang Jangka Panjang</td>
    <td>{{ $.uja1 }}</td>
    <td>{{ $.uja }}</td>
  </tr>
  <tr>
    <td><h3>JUMLAH TOTAL LIABILITAS<h3></td>
    <td>{{ $.total_liabilitas1 }}</td>
    <td>{{ $.total_liabilitas }}</td>
  </tr>
  <tr>
    <td><h3>ASET NETO<h3></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Aset Neto Wakaf</td>
    <td>{{ $.aset_neto1 }}</td>
    <td>{{ $.aset_neto }}</td>
  </tr>
  <tr>
    <td><h3>JUMLAH TOTAL ASET NETO<h3></td>
    <td>{{ $.jml_total_aset_neto1 }}</td>
    <td>{{ $.jml_total_aset_neto }}</td>
  </tr>
  <tr>
    <td><h3>JUMLAH TOTAL LIABILITAS DAN ASET NETO<h3></td>
    <td>{{ $.jml_liabilitas_aset_neto1 }}</td>
    <td>{{ $.jml_liabilitas_aset_neto }}</td>
  </tr>
</table>

</body>
</html>
