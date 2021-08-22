<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Laporan Posisi Keuangan</title>

	<!-- Bootstrap cdn 3.3.7 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Custom font montseraat -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">

  <style>
  
  /* Free template from github.com/nirajrajgor */
  .page-break {
      page-break-after: always;
  }
  /* td.no-data {
      border-collapse: collapse;
      border: 0px;
  }
  tr.padding-left{
      padding-left: 20px;
  } */
  .invoice-wrapper{
    margin: 20px auto;
    max-width: 700px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
  }
  .invoice-top{
    background-color: #fafafa;
    padding: 40px 60px;
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
  .invoice-top-left h4{
    margin-top: 30px;
    font-family: 'Montserrat', sans-serif;
  }
  .invoice-top-left h5{
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
  .invoice-top-right h5{
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
  .invoice-bottom-left h5, .invoice-bottom-left h4{
    font-family: 'Montserrat', sans-serif;
  }
  .invoice-bottom-left h4{
    font-weight: 400;
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
<body>

	<section class="back">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="invoice-wrapper">
						<div class="invoice-top">
							<div class="row">
								<div class="col-sm-6">
									<div class="invoice-top-left">
										<h2 class="client-company-name">Laporan Posisi Keuangan <br> BWUT MUI DIY</h2>
										<h5>Per 31 Desember {{ $year1 }} dan {{ $year2 }}</h5>
									</div>
								</div>
								
							</div>
						</div>
						<div class="invoice-bottom">
							<div class="row">
								
									<div class="invoice-bottom-right">
										<table class="table">
											<thead>
												<tr>
													<th></th>
													<th>{{ $year1 }}</th>
													<th>{{ $year2 }}</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><h2 class="client-company-name">ASET</h2></td>
                          <td></td>
                          <td></td>
												</tr>
												<tr>
                          <td><h4>ASET LANCAR<h4></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Kas Tunai</td>
                          <td>{{ $kastunai1 }}</td>
                          <td>{{ $kastunai }}</td>
                        </tr>
                        <tr>
                          <td>Kas Tabungan Wakaf</td>
                          <td>{{ $kastabwakaf1 }}</td>
                          <td>{{ $kastabwakaf }}</td>
                        </tr>
                        <tr>
                          <td>Kas Tabungan Bagi Hasil</td>
                          <td>{{ $kastabbagihasil1 }}</td>
                          <td>{{ $kastabbagihasil }}</td>
                        </tr>
                        <tr>
                          <td>Kas Tabungan Non Bagi Hasil</td>
                          <td>{{ $kastabnonbagihasil1 }}</td>
                          <td>{{ $kastabnonbagihasil }}</td>
                        </tr>
                        <tr>
                          <td>Kas Deposito</td>
                          <td>{{ $kasdeposito1 }}</td>
                          <td>{{ $kasdeposito }}</td>
                        </tr>
                        <tr>
                          <td>Piutang Jangka Pendek</td>
                          <td>{{ $pjp1 }}</td>
                          <td>{{ $pjp }}</td>
                        </tr>
                        <tr>
                          <td>Piutang Jangka Panjang</td>
                          <td>{{ $pja1 }}</td>
                          <td>{{ $pja }}</td>
                        </tr>
                        <tr>
                          <td>Persediaan</td>
                          <td>{{ $persediaan1 }}</td>
                          <td>{{ $persediaan }}</td>
                        </tr>
                        <tr>
                          <td><h4>ASET TIDAK LANCAR<h4></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td><h5><i>ASET TETAP</i><h5></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Tanah</td>
                          <td>{{ $tanah1 }}</td>
                          <td>{{ $tanah }}</td>
                        </tr>
                        <tr>
                          <td>Gedung dan Bangunan</td>
                          <td>{{ $gedung1 }}</td>
                          <td>{{ $gedung }}</td>
                        </tr>
                        <tr>
                          <td>Mesin dan Kendaraan</td>
                          <td>{{ $mesin1 }}</td>
                          <td>{{ $mesin }}</td>
                        </tr>
                        <tr>
                          <td>Peralatan dan Perlengkapan Kantor</td>
                          <td>{{ $peralatan1 }}</td>
                          <td>{{ $peralatan }}</td>
                        </tr>
                        <tr>
                          <td>Aset Lain-Lain</td>
                          <td>{{ $asetlain1 }}</td>
                          <td>{{ $asetlain }}</td>
                        </tr>
                        <tr>
                          <td><h5><i>ASET TIDAK LANCAR LAINNYA</i><h5></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Hak Sewa</td>
                          <td>{{ $haksewa1 }}</td>
                          <td>{{ $haksewa }}</td>
                        </tr>
                        <tr>
                          <td><h4><b>TOTAL ASET</b><h4></td>
                          <td>{{ $totalaset1 }}</td>
                          <td>{{ $totalaset }}</td>
                        </tr>
                        <tr>
                          <td><h2 class="client-company-name"><div class="page-break"></div>LIABILITAS<h2></td>
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
                          <td>{{ $wtjp1 }}</td>
                          <td>{{ $wtjp }}</td>
                        </tr>
                        <tr>
                          <td>Utang Biaya</td>
                          <td>{{ $utangbiaya1 }}</td>
                          <td>{{ $utangbiaya }}</td>
                        </tr>
                        <tr>
                          <td><h4>LIABILITAS JANGKA PANJANG<h4></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Wakaf Temporer Jangka Panjang</td>
                          <td>{{ $wtja1 }}</td>
                          <td>{{ $wtja }}</td>
                        </tr>
                        <tr>
                          <td>Utang Jangka Panjang</td>
                          <td>{{ $uja1 }}</td>
                          <td>{{ $uja }}</td>
                        </tr>
                        <tr>
                          <td><h4>JUMLAH TOTAL LIABILITAS<h4></td>
                          <td>{{ $total_liabilitas1 }}</td>
                          <td>{{ $total_liabilitas }}</td>
                        </tr>
                        <tr>
                          <td><h2 class="client-company-name">ASET NETO<h2></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Aset Neto Wakaf</td>
                          <td>{{ $aset_neto1 }}</td>
                          <td>{{ $aset_neto }}</td>
                        </tr>
                        <tr>
                          <td><h4>JUMLAH TOTAL ASET NETO<h4></td>
                          <td>{{ $jml_total_aset_neto1 }}</td>
                          <td>{{ $jml_total_aset_neto }}</td>
                        </tr>
                        <tr>
                          <td><h4>JUMLAH TOTAL LIABILITAS DAN ASET NETO<h4></td>
                          <td>{{ $jml_liabilitas_aset_neto1 }}</td>
                          <td>{{ $jml_liabilitas_aset_neto }}</td>
                        </tr>
												<tr style="height: 40px;"></tr>
											</tbody>
										</table>
										
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-xs-12">
									<hr class="divider">
								</div>
								<div class="col-sm-4">
									<h6 class="text-left">acme.com</h6>
								</div>
								<div class="col-sm-4">
									<h6 class="text-center">contact@acme.com</h6>
								</div>
								<div class="col-sm-4">
									<h6 class="text-right">+91 8097678988</h6>
								</div>
							</div>
							<div class="bottom-bar"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	

	<!-- jquery slim version 3.2.1 minified -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g=" crossorigin="anonymous"></script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>
</html>