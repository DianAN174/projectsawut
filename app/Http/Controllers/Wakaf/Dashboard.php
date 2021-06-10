<?php

namespace App\Http\Controllers\Wakaf;
use App\Models\DataWakif;
use App\Models\ModelPengelolaan\KasTabNonBagiHasil;
use App\Models\ModelPelunasan\Pelunasan;
use App\Models\ModelPenyaluranManfaat\Penyaluran;
use App\Models\ModelPenyaluranManfaat\PiutangJangkaPendek;
use App\Models\ModelPenyaluranManfaat\PiutangJangkaPanjang;

use App\Models\User;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Mockery\Exception;


Class Dashboard
{
    public function Dashboard(Request $request){
    $year = Carbon::now()->format('Y');

    $totalPenerimaan = Penyaluran::where('penyaluran','1')->count('nominal_peminjaman');
    $totalWakif = DataWakif::distinct()->count('nama_wakif');
    $totalPenerima = Penyaluran::distinct()->count('nama_penerima');

    //Grafik Pendapatan Wakaf
    
    $pendapatanWakaf_2TahunSblm = DataWakif::whereYear('created_at', '=', ($year-2))->count('nominal'); 
    $pendapatanWakaf_1TahunSblm = DataWakif::whereYear('created_at', '=', ($year-1))->count('nominal');
    $pendapatanWakaf_TahunIni = DataWakif::whereYear('created_at', '=', ($year))->count('nominal');
    
    //$pendapatanWakaf = DataWakif::count('nominal');
    //->select(DB::raw("DATE_FORMAT(created_at, '%Y') new_date"),  DB::raw("YEAR(created_at) year"))
    //->groupBy('year')
    //->get();

    //Grafik Penyaluran Manfaat
    $penyaluranManfaat_2TahunSblm = Penyaluran::where('penyaluran','1')->whereYear('created_at', '=', ($year-2))->count('nominal_peminjaman'); 
    $penyaluranManfaat_1TahunSblm = Penyaluran::where('penyaluran','1')->whereYear('created_at', '=', ($year-1))->count('nominal_peminjaman');
    $penyaluranManfaat_TahunIni = Penyaluran::where('penyaluran','1')->whereYear('created_at', '=', ($year))->count('nominal_peminjaman');
    
    //$penyaluranManfaat = Penyaluran::where('penyaluran','1')->count('nominal_peminjaman');
    //->select(DB::raw("DATE_FORMAT(created_at, '%Y') new_date"),  DB::raw("YEAR(created_at) year"))
    //->groupBy('year')
    //->get();
  
     $data = [$totalPenerimaan, $totalWakif, $totalPenerima, $pendapatanWakaf_2TahunSblm, $pendapatanWakaf_1TahunSblm, $pendapatanWakaf_TahunIni, $penyaluranManfaat_2TahunSblm, $penyaluranManfaat_1TahunSblm, $penyaluranManfaat_TahunIni];
 
     return Response::HttpResponse(200, $data, "Index", false);
    }
}