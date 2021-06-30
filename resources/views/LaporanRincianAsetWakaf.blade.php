<!DOCTYPE html>
<html>
<title> {{ $title }}</title>
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

<h1>Laporan Rincian Aset Wakaf<br>
BWUT MUI DIY<br>
Per 31 Desember {{ $year1 }} dan {{ $year2 }}</h1>

<table style="width:100%">
<tr>
    <th></th>
    <th></th>
    <th><h3>31 Des {{ $year1 }}<h3></th> 
    <th></th>
    <th></th>
    <th><h3>31 Des {{ $year2 }}<h3></th>
  </tr>
  <tr>
    <th></th>
    <th>Wakif</th> 
    <th>Hasil Pengelolaan dan Pengembangan</th>
    <th>Jumlah</th>
    <th>Wakif</th> 
    <th>Hasil Pengelolaan dan Pengembangan</th>
    <th>Jumlah</th>
  </tr>
  <tr>
    <td>Kas dan Setara Kas</td>
    <td>{{ $kasWakif1 }}</td>
    <td>{{ $kasHasil1 }}</td>
    <td>{{ $kasJumlah1 }}</td>
    <td>{{ $kasWakif2 }}</td>
    <td>{{ $kasHasil2 }}</td>
    <td>{{ $kasJumlah2 }}</td>
  </tr>
  <tr>
    <td>Piutang</td>
    <td></td>
    <td>{{ $piutangHasil1 }}</td>
    <td>{{ $piutangJumlah1 }}</td>
    <td></td>
    <td>{{ $piutangHasil2 }}</td>
    <td>{{ $piutangJumlah2 }}</td>
  </tr>
  <tr>
    <td>Bilyet Deposito</td>
    <td>{{ $bilyetDepositoWakif1 }}</td>
    <td>{{ $bilyetDepositoHasil1 }}</td>
    <td>{{ $bilyetDepositoJumlah1 }}</td>
    <td>{{ $bilyetDepositoWakif2 }}</td>
    <td>{{ $bilyetDepositoHasil2 }}</td>
    <td>{{ $bilyetDepositoJumlah2 }}</td>
  </tr>
  <tr>
    <td>Logam Mulia</td>
    <td>{{ $logamMuliaWakif1 }}</td>
    <td>{{ $logamMuliaHasil1 }}</td>
    <td>{{ $logamMuliaJumlah1 }}</td>
    <td>{{ $logamMuliaWakif2 }}</td>
    <td>{{ $logamMuliaHasil2 }}</td>
    <td>{{ $logamMuliaJumlah2 }}</td>
  </tr>
  <tr>
    <td>Aset Lancar Lain</td>
    <td>{{ $asetLancarLainWakif1 }}</td>
    <td>{{ $asetLancarLainHasil1 }}</td>
    <td>{{ $asetLancarLainJumlah1 }}</td>
    <td>{{ $asetLancarLainWakif2 }}</td>
    <td>{{ $asetLancarLainHasil2 }}</td>
    <td>{{ $asetLancarLainJumlah2 }}</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Hak Sewa</td>
    <td>{{ $hakSewaWakif1 }}</td>
    <td>{{ $hakSewaHasil1 }}</td>
    <td>{{ $hakSewaJumlah1 }}</td>
    <td>{{ $hakSewaWakif2 }}</td>
    <td>{{ $hakSewaHasil2 }}</td>
    <td>{{ $hakSewaJumlah2 }}</td>
  </tr>
  <tr>
    <td>Investasi pada Entitas Lain</td>
    <td>{{ $investasiPadaEntitasLainWakif1 }}</td>
    <td>{{ $investasiPadaEntitasLainHasil1 }}</td>
    <td>{{ $investasiPadaEntitasLainJumlah1 }}</td>
    <td>{{ $investasiPadaEntitasLainWakif2 }}</td>
    <td>{{ $investasiPadaEntitasLainHasil2 }}</td>
    <td>{{ $investasiPadaEntitasLainJumlah2 }}</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Aset Tetap</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Hak Atas Tanah</td>
    <td>{{ $hakTanahWakif1 }}</td>
    <td>{{ $hakTanahHasil1 }}</td>
    <td>{{ $hakTanahJumlah1 }}</td>
    <td>{{ $hakTanahWakif2 }}</td>
    <td>{{ $hakTanahHasil2 }}</td>
    <td>{{ $hakTanahJumlah2 }}</td>
  </tr>
  <tr>
    <td>Bangunan</td>
    <td>{{ $bangunanWakif1 }}</td>
    <td>{{ $bangunanHasil1 }}</td>
    <td>{{ $bangunanJumlah1 }}</td>
    <td>{{ $bangunanWakif2 }}</td>
    <td>{{ $bangunanHasil2 }}</td>
    <td>{{ $bangunanJumlah2 }}</td>
  </tr>
  <tr>
    <td>Hak Milik Rumah</td>
    <td>{{ $hakMilikRumahWakif1 }}</td>
    <td>{{ $hakMilikRumahHasil1 }}</td>
    <td>{{ $hakMilikRumahJumlah1 }}</td>
    <td>{{ $hakMilikRumahWakif2 }}</td>
    <td>{{ $hakMilikRumahHasil2 }}</td>
    <td>{{ $hakMilikRumahJumlah2 }}</td>
  </tr>
  <tr>
    <td>Kendaraan</td>
    <td>{{ $kendaraanWakif1 }}</td>
    <td>{{ $kendaraanHasil1 }}</td>
    <td>{{ $kendaraanJumlah1 }}</td>
    <td>{{ $kendaraanWakif2 }}</td>
    <td>{{ $kendaraanHasil2 }}</td>
    <td>{{ $kendaraanJumlah2 }}</td>
  </tr>
  <tr>
    <td>Lainnya</td>
    <td>{{ $lainnyaWakif1 }}</td>
    <td>{{ $lainnyaHasil1 }}</td>
    <td>{{ $lainnyaJumlah1 }}</td>
    <td>{{ $lainnyaWakif2 }}</td>
    <td>{{ $lainnyaHasil2 }}</td>
    <td>{{ $lainnyaJumlah2 }}</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Aset Tak Berwujud</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Hak Kekayakaan Intelektual</td>
    <td>{{ $HKIWakif1 }}</td>
    <td>{{ $HKIHasil1 }}</td>
    <td>{{ $HKIJumlah1 }}</td>
    <td>{{ $HKIWakif2 }}</td>
    <td>{{ $HKIHasil2 }}</td>
    <td>{{ $HKIJumlah2 }}</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Aset Tidak Lancar Lainnya</td>
    <td>{{ $asetTidakLancarLainWakif1 }}</td>
    <td>{{ $asetTidakLancarLainHasil1 }}</td>
    <td>{{ $asetTidakLancarLainJumlah1 }}</td>
    <td>{{ $asetTidakLancarLainWakif2 }}</td>
    <td>{{ $asetTidakLancarLainHasil2 }}</td>
    <td>{{ $asetTidakLancarLainJumlah2 }}</td>
  </tr>
  <td><h3>JUMLAH ASET<h3></td>
    <td>{{ $jumlahAsetWakif1 }}</td>
    <td>{{ $jumlahAsetHasil1 }}</td>
    <td>{{ $jumlahAset1 }}</td>
    <td>{{ $jumlahAsetWakif2 }}</td>
    <td>{{ $jumlahAsetHasil2 }}</td>
    <td>{{ $jumlahAset2 }}</td>
  </tr>
</table>

</body>
</html>
