<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Laporan Rincian Aset Wakaf</title>

	<!-- Bootstrap cdn 3.3.7 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh3u" crossorigin="anonymous">

	<!-- Custom font montseraat -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">

  <style>
  
  /* Free template from github.com/nirajrajgor */
  .page-break {
      page-break-inside: always;
  }
  .table th {
      text-align: center;
  }
  .table tr {
      page-break-inside: auto;
  }
  .table tr td {
      page-break-inside: auto;
  }

  /* td.no-data {
      border-collapse: collapse;
      border: 0px;
  }
  tr.padding-left{
      padding-left: 20px;
  } */

  @page { margin: 60px 25px; }
  .invoice-wrapper{
    margin: 20px auto auto 20px;
    max-width: 1000px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
  }
  .invoice-top{
    background-color: #fafafa;
    padding: 20px 60px;
  }
  /*
  Invoice-top-left refers to the client name & address, service provided
  */
  .invoice-top-left{
    /* margin-top: 60px; */
      margin-top: 0px;
  }
  .invoice-top-left h2 , .invoice-top-left h6{
    line-height: 1.5;
    font-family: 'Montserrat', sans-serif;
  }
  .invoice-top-left h3{
    margin-top: 30px;
    font-family: 'Montserrat', sans-serif;
  }
  .invoice-top-left h4{
    line-height: 1.4;
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
  }
  .client-company-name{
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 0;
  }
  .client-address{
    font-size: 14px;
    margin-top: 5px;
    color: rgba(0,0,0,0.75);
  }
  
  /*
  Invoice-top-right refers to the our name & address, logo and date
  */
  .invoice-top-right h2 , .invoice-top-right h6{
    text-align: right;
    line-height: 1.5;
    font-family: 'Montserrat', sans-serif;
  }
  .invoice-top-right h4{
    line-height: 1.4;
      font-family: 'Montserrat', sans-serif;
      font-weight: 400;
      text-align: right;
      margin-top: 0;
  }
  .our-company-name{
    font-size: 16px;
      font-weight: 600;
      margin-bottom: 0;
  }
  .our-address{
    font-size: 13px;
    margin-top: 0;
    color: rgba(0,0,0,0.75);
  }
  .logo-wrapper{ overflow: auto; }
  
  /*
  Invoice-bottom refers to the bottom part of invoice template
  */
  .invoice-bottom{
    background-color: #ffffff;
    padding: 40px 60px;
    position: relative;
  }
  .title{
    font-size: 30px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    margin-bottom: 30px;
  }
  /*
  Invoice-bottom-left
  */
  .invoice-bottom-left {
    font-family: 'Montserrat', sans-serif;
  }
  .invoice-bottom-left h4, .invoice-bottom-left h3, invoice-bottom-left h3, invoice-bottom-left h2{
    font-family: 'Montserrat', sans-serif;
  }
  .invoice-bottom-left h4{
    font-size: 16px;
    font-weight: 400;
  }
  .invoice-bottom-left h3{
    font-size: 18px;
    font-weight: 500;
  }
  .invoice-bottom-left h2{
    font-size: 20px;
    font-weight: 600;
  }
  .terms{
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    margin-top: 40px;
  }
  .divider{
    margin-top: 50px;
      margin-bottom: 5px;
  }
  /*
  bottom-bar is colored bar located at bottom of invoice-card
  */
  .bottom-bar{
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 26px;
    background-color: #323149;
  }
  </style>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="margin">

<section class="back">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="invoice-wrapper">
						<div class="invoice-top">
							<div class="row">
								<div class="col-sm-6">
									<div class="invoice-top-left">
										<h2 class="client-company-name">Laporan Rincian Aset Wakaf <br> BWUT MUI DIY</h2>
										<h4>Per 31 Desember {{ $year1 }} dan {{ $year2 }}</h4>
									</div>
								</div>
								
							</div>
						</div>
						<div class="invoice-bottom">
							<div class="row">
								
									<div class="invoice-bottom-left">
										<table class="table table-bordered" style="width:100%">
											<thead>
                        <tr>
                          <th></th>
                          <th colspan="3"><h3>31 Desember {{ $year1 }}<h3></th>
                          <th colspan="3"><h3>31 Desember {{ $year2 }}<h3></th>
                        </tr>
                        <tr>
                          <th></th>
                          <th style="vertical-align: middle;">Wakif</th> 
                          <th style="vertical-align: middle;">Hasil Pengelolaan dan Pengembangan</th>
                          <th style="vertical-align: middle;">Jumlah</th>
                          <th style="vertical-align: middle;">Wakif</th> 
                          <th style="vertical-align: middle;">Hasil Pengelolaan dan Pengembangan</th>
                          <th style="vertical-align: middle;">Jumlah</th>
                        </tr>
											</thead>
											<tbody>
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
                          <td> - </td>
                          <td>{{ $piutangHasil1 }}</td>
                          <td>{{ $piutangJumlah1 }}</td>
                          <td> - </td>
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
                          <td colspan="7"></td>
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
                          <td colspan="7"></td>
                        </tr>
                        <tr>
                          <td colspan="7"><h4>Aset Tetap<h4></td>
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
                          <td colspan="7"></td>
                        </tr>
                        <tr>
                          <td colspan="7"><h4>Aset Tak Berwujud</h4></td>
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
                          <td colspan="7"></td>
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
                        <tr>
                          <td><h4>JUMLAH ASET</h4></td>
                          <td>{{ $jumlahAsetWakif1 }}</td>
                          <td>{{ $jumlahAsetHasil1 }}</td>
                          <td>{{ $jumlahAset1 }}</td>
                          <td>{{ $jumlahAsetWakif2 }}</td>
                          <td>{{ $jumlahAsetHasil2 }}</td>
                          <td>{{ $jumlahAset2 }}</td>
                        </tr>
											</tbody>
										</table>
										
									</div>
								</div>
								<div class="clearfix"></div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	


</body>
</html>