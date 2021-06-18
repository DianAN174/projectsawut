<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/auth/login', 'App\Http\Controllers\Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','App\Http\Controllers\Auth\ApiAuthController@register')->name('register.api');
});

Route::middleware('cors','auth:api')->group(function () {

    Route::get('/auth/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', 'App\Http\Controllers\Auth\ApiAuthController@logout')->name('logout.api');

    
    //dashboard
    Route::get('/wakaf/', 'App\Http\Controllers\Wakaf\Dashboard@Dashboard');

    //fitur penerimaan
    Route::post('/wakaf/penerimaan/create', 'App\Http\Controllers\Wakaf\Penerimaan@Create');
    Route::get('/wakaf/penerimaan', 'App\Http\Controllers\Wakaf\Penerimaan@Index');
    Route::get('/wakaf/penerimaan/{id}/edit', 'App\Http\Controllers\Wakaf\Penerimaan@Edit');
    Route::put('/wakaf/penerimaan/{id}', 'App\Http\Controllers\Wakaf\Penerimaan@Update');
    Route::delete('/wakaf/penerimaan/{id}', 'App\Http\Controllers\Wakaf\Penerimaan@Delete');
    Route::get('/wakaf/penerimaan/dropdown-jenis', 'App\Http\Controllers\Wakaf\Penerimaan@DropdownJenisWakaf');
    Route::get('/wakaf/search-penerimaan', 'App\Http\Controllers\Wakaf\Penerimaan@Search');

    //fitur pengelolaan wakaf
    Route::get('/wakaf/pengelolaan', 'App\Http\Controllers\Wakaf\PengelolaanWakaf@Index');
    Route::post('/wakaf/pengelolaan/pindah', 'App\Http\Controllers\Wakaf\PengelolaanWakaf@PindahSaldo');

    //fitur penyaluran manfaat
    Route::get('/wakaf/penyaluran', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Index');
    //Route::post('/wakaf/penyaluran/create/', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Create');
    Route::post('/wakaf/penyaluran/input-1', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@ModalCreate');
    Route::post('/wakaf/penyaluran/input-2/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@ModalKelayakanFirst');
    Route::post('/wakaf/penyaluran/input-3/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@ModalKelayakanSecond');
    //coba store
    Route::post('/wakaf/penyaluran/store/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Store');
    Route::get('/wakaf/penyaluran/{id}/edit', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Edit');
    Route::put('/wakaf/penyaluran/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Update');
    Route::delete('/wakaf/penyaluran/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Delete');
    Route::get('/wakaf/penyaluran/dropdown-sumber', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@DropdownSumberBiaya');
    Route::get('/wakaf/penyaluran/dropdown-jenis', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@DropdownJenisPiutang');
    Route::get('/wakaf/search-penyaluran', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Search');
    Route::put('/wakaf/penyaluran/kelayakan/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@TesKelayakan');
    Route::put('/wakaf/penyaluran/persetujuan/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Persetujuan');
    Route::put('/wakaf/penyaluran/penyaluran/{id}', 'App\Http\Controllers\Wakaf\PenyaluranManfaat@Penyaluran');

    //fitur pengajuan biaya operasional
    Route::get('/wakaf/pengajuan/', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Index');
    Route::post('/wakaf/pengajuan/create', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Create');
    Route::get('/wakaf/pengajuan/{id}/edit', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Edit');
    Route::put('/wakaf/pengajuan/{id}', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Update');
    Route::delete('/wakaf/pengajuan/{id}', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Delete');
    Route::get('/wakaf/pengajuan/dropdown-kategori-biaya', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@DropdownKategoriBiaya');
    Route::get('/wakaf/pengajuan/dropdown-jenis-biaya/{id}', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@DropdownJenisBiaya');
    Route::get('/wakaf/pengajuan/dropdown-jenis-biaya-2/{id}', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@DropdownJenisBiayaDua');
    Route::get('/wakaf/pengajuan/dropdown-jenis-biaya-3/{id}', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@DropdownJenisBiayaTiga');
    Route::get('/wakaf/search-pengajuan', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Search');
    Route::put('/wakaf/pengajuan/persetujuan/{id}', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Persetujuan');
    Route::put('/wakaf/pengajuan/pencairan/{id}', 'App\Http\Controllers\Wakaf\PengajuanBiayaOperasional@Pencairan');

    //fitur pelunasan piutang
    Route::get('/wakaf/pelunasan/', 'App\Http\Controllers\Wakaf\PelunasanPiutang@Index');
    Route::post('/wakaf/pelunasan/create', 'App\Http\Controllers\Wakaf\PelunasanPiutang@Create');
    Route::get('/wakaf/pelunasan/{id}/edit', 'App\Http\Controllers\Wakaf\PelunasanPiutang@Edit');
    Route::put('/wakaf/pelunasan/{id}', 'App\Http\Controllers\Wakaf\PelunasanPiutang@Update');
    Route::delete('/wakaf/pelunasan/{id}', 'App\Http\Controllers\Wakaf\PelunasanPiutang@Delete');
    Route::get('/wakaf/search-pelunasan', 'App\Http\Controllers\Wakaf\PelunasanPiutang@Search');
    //Route::put('/wakaf/pelunasan/persetujuan/{id}', 'App\Http\Controllers\Wakaf\PelunasanPiutang@Persetujuan');

    //fitur data aset tetap
    Route::get('/wakaf/data-aset-tetap/', 'App\Http\Controllers\Wakaf\DataAsetTetapController@Index');
    Route::post('/wakaf/data-aset-tetap/create', 'App\Http\Controllers\Wakaf\DataAsetTetapController@Create');
    Route::get('/wakaf/data-aset-tetap/{id}/detail', 'App\Http\Controllers\Wakaf\DataAsetTetapController@Detail');
    Route::get('/wakaf/data-aset-tetap/{id}/edit', 'App\Http\Controllers\Wakaf\DataAsetTetapController@Edit');
    Route::put('/wakaf/data-aset-tetap/{id}', 'App\Http\Controllers\Wakaf\DataAsetTetapController@Update');
    Route::delete('/wakaf/data-aset-tetap/{id}', 'App\Http\Controllers\Wakaf\DataAsetTetapController@Delete');
    Route::get('/wakaf/data-aset-tetap/dropdown-kelompok', 'App\Http\Controllers\Wakaf\DataAsetTetapController@DropdownKelompok');
    Route::get('/wakaf/search-data-aset-tetap', 'App\Http\Controllers\Wakaf\DataAsetTetapController@Search');
    
    //fitur data utang
    Route::get('/wakaf/data-utang/', 'App\Http\Controllers\Wakaf\DataUtangController@Index');
    Route::post('/wakaf/data-utang/create', 'App\Http\Controllers\Wakaf\DataUtangController@Create');
    Route::get('/wakaf/data-utang/{id}/edit', 'App\Http\Controllers\Wakaf\DataUtangController@Edit');
    Route::put('/wakaf/data-utang/{id}', 'App\Http\Controllers\Wakaf\DataUtangController@Update');
    Route::delete('/wakaf/data-utang/{id}', 'App\Http\Controllers\Wakaf\DataUtangController@Delete');
    Route::get('/wakaf/data-utang/dropdown-kategori', 'App\Http\Controllers\Wakaf\DataUtangController@DropdownKategori');
    Route::get('/wakaf/search-data-utang', 'App\Http\Controllers\Wakaf\DataUtangController@Search');
    Route::put('/wakaf/data-utang/persetujuan/{id}', 'App\Http\Controllers\Wakaf\DataUtangController@Persetujuan');
    
    //fitur data akun
    Route::get('/wakaf/data-akun/', 'App\Http\Controllers\Wakaf\DataAkun@Index');
    Route::get('/wakaf/data-akun/edit', 'App\Http\Controllers\Wakaf\DataAkun@EditProfil');
    Route::put('/wakaf/data-akun/', 'App\Http\Controllers\Wakaf\DataAkun@Update');

    //fitur daftar pengguna
    Route::get('/wakaf/daftar-pengguna/', 'App\Http\Controllers\Wakaf\DaftarPengguna@Index');
    Route::post('/wakaf/daftar-pengguna/create', 'App\Http\Controllers\Wakaf\DaftarPengguna@Create');
    Route::get('/wakaf/daftar-pengguna/{id}/edit', 'App\Http\Controllers\Wakaf\DaftarPengguna@Edit');
    Route::put('/wakaf/daftar-pengguna/{id}', 'App\Http\Controllers\Wakaf\DaftarPengguna@Update');
    Route::delete('/wakaf/daftar-pengguna/{id}', 'App\Http\Controllers\Wakaf\DaftarPengguna@Delete');
    Route::get('/wakaf/daftar-pengguna/dropdown-peran', 'App\Http\Controllers\Wakaf\DaftarPengguna@DropdownPeran');
    Route::get('/wakaf/search-daftar-pengguna', 'App\Http\Controllers\Wakaf\DaftarPengguna@Search');
    Route::put('/wakaf/daftar-pengguna/status/{id}', 'App\Http\Controllers\Wakaf\DaftarPengguna@Status');

});
