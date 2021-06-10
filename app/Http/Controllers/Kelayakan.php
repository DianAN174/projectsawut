<?php

namespace App\Http\Controllers;

use App\Models\ModelPenyaluranManfaat\Penyaluran;
use App\Models\User;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;

class Kelayakan
{
    public function TesKelayakan(Request $request, $id)
        {
            $validator = Validator::make($request->all(), [
                'nominal_peminjaman' => 'required',
                'lama_usaha' => 'required',
                'avg_omset' => 'required',
                'laba_bersih' => 'required',
                'jumlah_karyawan' => 'required',
                'proporsi_penjualan_tunai' => 'required',
                'jangka_waktu_peminjaman' => 'required',
                'hutang_lain' => 'required',
                'nominal_hutang_lain' => 'required',
                'angsuranperbulan_lain' => 'required',
                'keterlambatan_pelunasan' => 'required',
                'jml_tanggungan_keluarga' => 'required',
                'pengeluaran_keluarga_perbulan' => 'required',
                'penilaian_masyarakat' => 'required',
                'sikap_kooperatif' => 'required',
                'sikap_tanggung_jawab' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            $skor_capacity=0;
            $nominal_peminjaman=chr(2);
            $lama_usaha=chr(2);
            $avg_omset=chr(2);
            $laba_bersih=chr(2);
            $jumlah_karyawan=chr(2);
            //proporsi penjualan yg didapat dg cara tunai
            $proporsi_penjualan_tunai=chr(2);

            switch ($nominal_peminjaman)
            {
                case "a":
                    $skor_capacity += 4;
                break;
                case "b":
                    $skor_capacity += 3;
                break;
                case "c":
                    $skor_capacity += 2;
                break;
                case "d":
                    $skor_capacity += 1;
                break;
            }

            switch ($lama_usaha)
            {
                case "a":
                    $skor_capacity += 1;
                break;
                case "b":
                    $skor_capacity += 2;
                break;
                case "c":
                    $skor_capacity += 3;
                break;
                case "d":
                    $skor_capacity += 4;
                break;
                case "e":
                    $skor_capacity += 5;
                break;
            }

            switch ($avg_omset)
            {
                case "a":
                    $skor_capacity += 1;
                break;
                case "b":
                    $skor_capacity += 2;
                break;
                case "c":
                    $skor_capacity += 3;
                break;
                case "d":
                    $skor_capacity += 4;
                break;
                case "e":
                    $skor_capacity += 5;
                break;
            }

            switch ($laba_bersih)
            {
                case "a":
                    $skor_capacity += 1;
                break;
                case "b":
                    $skor_capacity += 2;
                break;
                case "c":
                    $skor_capacity += 3;
                break;
                case "d":
                    $skor_capacity += 4;
                break;
                case "e":
                    $skor_capacity += 5;
                break;
            }

            switch ($jumlah_karyawan)
            {
                case "a":
                    $skor_capacity += 1;
                break;
                case "b":
                    $skor_capacity += 2;
                break;
                case "c":
                    $skor_capacity += 3;
                break;
                case "d":
                    $skor_capacity += 4;
                break;
            }

            switch ($proporsi_penjualan_tunai)
            {
                case "a":
                    $skor_capacity += 1;
                break;
                case "b":
                    $skor_capacity += 2;
                break;
                case "c":
                    $skor_capacity += 3;
                break;
                case "d":
                    $skor_capacity += 4;
                break;
                case "e":
                    $skor_capacity += 5;
                break;
            }

            $skor_collateral=0;
            $jangka_waktu_peminjaman=chr(2);

            switch ($jangka_waktu_peminjaman)
            {
                case "a":
                    $skor_collateral += 1;
                break;
                case "b":
                    $skor_collateral += 2;
                break;
                case "c":
                    $skor_collateral += 3;
                break;
                case "d":
                    $skor_collateral += 4;
                break;
            }

            $skor_capital=0;
            $hutang_lain=chr(2);
            $nominal_hutang_lain=chr(2);
            $angsuranperbulan_lain=chr(2);
            $keterlambatan_pelunasan=chr(2);

            switch ($hutang_lain)
            {
                case "a":
                    $skor_capital += 0;
                break;
                case "b":
                    $skor_capital += 1;
                break;
            }

            switch ($nominal_hutang_lain)
            {
                case "a":
                    $skor_capital -= 1;
                break;
                case "b":
                    $skor_capital -= 2;
                break;
                case "c":
                    $skor_capital -= 3;
                break;
                case "d":
                    $skor_capital -= 4;
                break;
                case "e":
                    $skor_capital -= 5;
                break;
            }

            switch ($angsuranperbulan_lain)
            {
                case "a":
                    $skor_capital -= 1;
                break;
                case "b":
                    $skor_capital -= 2;
                break;
                case "c":
                    $skor_capital -= 3;
                break;
                case "d":
                    $skor_capital -= 4;
                break;
                case "e":
                    $skor_capital -= 5;
                break;
            }

            switch ($keterlambatan_pelunasan)
            {
                case "a":
                    $skor_capital += 0;
                break;
                case "b":
                    $skor_capital -= 1;
                break;
                case "c":
                    $skor_capital -= 2;
                break;
                case "d":
                    $skor_capital -= 3;
                break;
            }

            $skor_kondisi_ekonomi=0;
            $jml_tanggungan_keluarga=chr(2);
            $pengeluaran_keluarga_perbulan=chr(2);

            switch ($jml_tanggungan_keluarga)
            {
                case "a":
                    $skor_kondisi_ekonomi += 1;
                break;
                case "b":
                    $skor_kondisi_ekonomi += 2;
                break;
                case "c":
                    $skor_kondisi_ekonomi += 3;
                break;
            }

            switch ($pengeluaran_keluarga_perbulan)
            {
                case "a":
                    $skor_kondisi_ekonomi += 1;
                break;
                case "b":
                    $skor_kondisi_ekonomi += 2;
                break;
                case "c":
                    $skor_kondisi_ekonomi += 3;
                break;
            }

            $skor_character=0;
            $penilaian_masyarakat=chr(2);
            $sikap_kooperatif=chr(2);
            $sikap_tanggung_jawab=chr(2);

            switch ($penilaian_masyarakat)
            {
                case "a":
                    $skor_character += 1;
                break;
                case "b":
                    $skor_character += 2;
                break;
                case "c":
                    $skor_character += 3;
                break;
            }

            switch ($sikap_kooperatif)
            {
                case "a":
                    $skor_character += 1;
                break;
                case "b":
                    $skor_character += 2;
                break;
            }

            switch ($sikap_tanggung_jawab)
            {
                case "a":
                    $skor_character += 1;
                break;
                case "b":
                    $skor_character += 2;
                break;
                case "c":
                    $skor_character += 3;
                break;
            }

            $skor_max=44;
            $skor_total=($skor_capacity+$skor_collateral+$skor_capital+$skor_kondisi_ekonomi+$skor_character);
            $skor_akhir=($skor_total/$skor_max)*100;

            $penyaluranBiaya = Penyaluran::find($id);
            //dd($penyaluranBiaya);

            if($skor_akhir>50)
            {

                $penyaluranBiaya->kelayakan = '1';
            }
            else{
                $penyaluranBiaya->kelayakan = '0';
            }

            $newPenyaluranBiaya = $penyaluranBiaya->save();

                if (!$newPenyaluranBiaya) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                }
                
            return Response::HttpResponse(200, $newPenyaluranBiaya, "Success", false);
        }
}