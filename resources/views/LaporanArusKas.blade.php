<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Laporan Arus Kas</title>

	<!-- Bootstrap cdn 3.3.7 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh3u" crossorigin="anonymous">

	<!-- Custom font montseraat -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">

  <style>
  
  /* Free template from github.com/nirajrajgor */
  .page-break {
      page-break-inside: always;
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
    max-width: 700px;
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
										<h2 class="client-company-name">Laporan Arus Kas <br> BWUT MUI DIY</h2>
										<h4>Per 31 Desember {{ $year2 }}</h4>
									</div>
								</div>
								
							</div>
						</div>
						<div class="invoice-bottom">
							<div class="row">
								
									<div class="invoice-bottom-left">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th></th>
													<th>31 Desember {{ $year2 }}</th>
												</tr>
											</thead>
											<tbody>
                        <tr>
                          <td colspan="2"><h2>AKTIVITAS OPERASI</h2></td>
                        </tr>
                        <tr>
                          <td colspan="2"><h3>PENERIMAAN</h3></td>
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
                          <td><h4>TOTAL PENERIMAAN</h4></td>
                          <td> {{ $total_penerimaan_operasi2 }}</td>
                        </tr>
                        <tr>
                          <td colspan="2"><h3>PENGELUARAN</h3></td>
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
                          <td><h4>TOTAL PENGELUARAN</h4></td>
                          <td>{{ $total_pengeluaran_operasi2 }}</td>
                        </tr>
                        <tr>
                          <td ><h3>KAS NETO DARI AKTIVITAS OPERASI</h3></td>
                          <td>{{ $kas_neto_operasi2 }}</td>
                        </tr>
                        <tr>
                          <td colspan="2"><h2>AKTIVITAS INVESTASI</h2></td>\
                        </tr>
                        <tr>
                          <td colspan="2"><h3>PENERIMAAN</h3></td>
                        </tr>
                        <tr>
                          <td>Penjualan Aktiva Tetap</td>
                          <td>{{ $penjualanaktivatetap2 }}</td>
                        </tr>
                        <tr>
                          <td colspan="2"><h3>PENGELUARAN</h3></td>
                        </tr>
                        <tr>
                          <td>Perolehan Aktiva Tetap</td>
                          <td>{{ $perolehanaktivatetap2 }}</td>
                        </tr>
                        <tr>
                          <td><h3>KAS NETO DARI AKTIVITAS INVESTASI</h3></td>
                          <td>{{ $kas_neto_investasi2 }}</td>
                        </tr>
                        <tr>
                          <td colspan="2"><h2>AKTIVITAS PENDANAAN</h2></td>
                        </tr>
                        <tr>
                          <td colspan="2"><h3>PENERIMAAN</h3></td>
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
                          <td colspan="2"><h3>PENGELUARAN</h3></td>
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
                          <td><h2>KENAIKAN/(PENURUNAN) NETO KAS</h2></td>
                          <td>{{ $kenaikan_penurunan_neto_kas2 }}</td>
                        </tr>
                        <tr>
                          <td><h2>KAS AWAL PERIODE</h2></td>
                          <td>{{ $kas_awal_periode2 }}</td>
                        </tr>
                        <tr>
                          <td><h2>KAS AKHIR PERIODE<h2></td>
                          <td>{{ $kas_akhir_periode2 }}</td>
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