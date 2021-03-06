<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\ModelPengelolaanLain\DataAsetTetap;
use App\Models\ModelPengelolaanLain\DataUtang;
use App\Models\ModelPengelolaanLain\AkunPersediaan;
use App\Models\ModelPengelolaanLain\AkunMesindanKendaraan;
use App\Models\ModelPengelolaanLain\AkunGedungdanBangunandanBangunandanBangunan;
use App\Models\ModelPengelolaanLain\AkunTanah;
use App\Models\ModelPengelolaanLain\AkunPeralatandanPerlengkapanKantor;
use App\Models\ModelPengelolaanLain\AkunAsetLainLain;

use App\Models\User;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class DataAsetTetapController
{
    protected $admin;

    public function __construct(User $user)
    {
        $this->admin = $user;
    }

    public function Create(Request $request)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_aset' => 'required|string|max:255',
                'kelompok' => 'required|in:persediaan,kendaraan,gedung,tanah,peralatan,asetlain',
                'tanggal_beli' => 'required|date_format:Y-m-d',
                'harga_perolehan' => 'required|numeric',
                'nilai_bersih' => 'required|numeric',
                'nilai_residu' => 'required|numeric',
                'umur_ekonomis' => 'required|numeric',
                'lokasi' => 'required|string|max:255',

                //akumulasi
                'nomor' => 'required|numeric',
                'departemen' => 'required|string|max:255',
                'akumulasi_beban' => 'required|numeric',
                'beban_per_tahun_ini' => 'required|numeric',
                'terhitung_tanggal' => 'required|date_format:Y-m-d',
                'nilai_buku' => 'required|numeric',
                'beban_per_bulan' => 'required|numeric',
                'nilai_penyusutan' => 'required|numeric',

                'keterangan' => 'required|string|max:255',
                'tanggal_transaksi' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataAsetTetap = new DataAsetTetap();

            $dataAsetTetap->nama_aset = $request->nama_aset;
            $dataAsetTetap->kelompok = $request->kelompok;
            $dataAsetTetap->tanggal_beli = $request->tanggal_beli;
            $dataAsetTetap->harga_perolehan = $request->harga_perolehan;
            $dataAsetTetap->nilai_residu = $request->nilai_residu;
            $dataAsetTetap->nilai_bersih = $request->nilai_bersih;
            $dataAsetTetap->harga_perolehan = $request->harga_perolehan;
            $dataAsetTetap->lokasi = $request->lokasi;
            $dataAsetTetap->nomor = $request->nomor;
            $dataAsetTetap->departemen = $request->departemen;
            $dataAsetTetap->akumulasi_beban = $request->akumulasi_beban;
            $dataAsetTetap->beban_per_tahun_ini = $request->beban_per_tahun_ini;
            $dataAsetTetap->terhitung_tanggal = $request->terhitung_tanggal;
            $dataAsetTetap->nilai_buku = $request->nilai_buku;
            $dataAsetTetap->beban_per_bulan = $request->beban_per_bulan;
            $dataAsetTetap->nilai_penyusutan = $request->nilai_penyusutan;
            $dataAsetTetap->created_by = $this->admin->name;
            $dataAsetTetap->modified_by = $this->admin->name;

            $newDataAsetTetap = $dataAsetTetap->save();

            if (!$newDataAsetTetap) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $kelompok = $dataAsetTetap->kelompok;

            switch ($kelompok) {
                case "kendaraan":
                    $newKendaraan = new AkunMesindanKendaraan();
                    $newKendaraan->tanggal_transaksi = $request->tanggal_transaksi;
                    $newKendaraan->keterangan = $request->keterangan;
                    $newKendaraan->saldo = $dataAsetTetap->harga_perolehan;
                    $newKendaraan->type = 'debit';
                    $newKendaraan->data_aset_tetap_id = $dataAsetTetap->id;
                    $newKendaraan = $newKendaraan->save();

                    if (!$newKendaraan) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "gedung":
                    $newGedung = new AkunGedungdanBangunan();
                    $newGedung->tanggal_transaksi = $request->tanggal_transaksi;
                    $newGedung->keterangan = $request->keterangan;
                    $newGedung->saldo = $dataAsetTetap->harga_perolehan;
                    $newGedung->type = 'debit';
                    $newGedung->data_aset_tetap_id = $dataAsetTetap->id;
                    $newGedung = $newGedung->save();

                    if (!$newGedung) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;

            case "tanah":
                    $newTanah = new AkunTanah();
                    $newTanah->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTanah->keterangan = $request->keterangan;
                    $newTanah->saldo = $dataAsetTetap->harga_perolehan;
                    $newTanah->type = 'debit';
                    $newTanah->data_aset_tetap_id = $dataAsetTetap->id;
                    $newTanah = $newTanah->save();

                    if (!$newTanah) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

            break;    
                
            case "persediaan":
                $newPersediaan = new AkunPersediaan();
                $newPersediaan->tanggal_transaksi = $request->tanggal_transaksi;
                $newPersediaan->keterangan = $request->keterangan;
                $newPersediaan->saldo = $dataAsetTetap->harga_perolehan;
                $newPersediaan->type = 'debit';
                $newPersediaan->data_aset_tetap_id = $dataAsetTetap->id;
                $newPersediaan = $newPersediaan->save();

                if (!$newPersediaan) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            break;    

            case "peralatan":
                $newPeralatan = new AkunPeralatandanPerlengkapanKantor();
                $newPeralatan->tanggal_transaksi = $request->tanggal_transaksi;
                $newPeralatan->keterangan = $request->keterangan;
                $newPeralatan->saldo = $dataAsetTetap->harga_perolehan;
                $newPeralatan->type = 'debit';
                $newPeralatan->data_aset_tetap_id = $dataAsetTetap->id;
                $newPeralatan = $newPeralatan->save();

                if (!$newPeralatan) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

           break;  

            case "asetlain":
                $newAsetLain = new AkunAsetLainLain();
                $newAsetLain->tanggal_transaksi = $request->tanggal_transaksi;
                $newAsetLain->keterangan = $request->keterangan;
                $newAsetLain->saldo = $dataAsetTetap->harga_perolehan;
                $newAsetLain->type = 'debit';
                $newAsetLain->data_aset_tetap_id = $dataAsetTetap->id;
                $newAsetLain = $newAsetLain->save();

                if (!$newAsetLain) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            break;   

                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newDataAsetTetap, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Detail(Request $request,$id)
    {
        try{
            $datas = DataAsetTetap::select('nomor','departemen','akumulasi_beban','beban_per_tahun_ini','terhitung_tanggal','nilai_buku','beban_per_bulan','nilai_penyusutan')
            ->where('id',$id)
            ->get();
            /* foreach ($datas as $d_key => $data) {
                
                $data["nomor"] = $data->DataAsetTetap['nomor'];
                $data["departemen"] = $data->DataAsetTetap['departemen'];
                $data["akumulasi_beban"] = $data->DataAsetTetap['akumulasi_beban'];
                $data["beban_per_tahun_ini"] = $data->DataAsetTetap['beban_per_tahun_ini'];
                $data["terhitung_tanggal"] = $data->DataAsetTetap['terhitung_tanggal'];
                $data["nilai_buku"] = $data->DataAsetTetap['nilai_buku'];
                $data["beban_per_bulan"] = $data->DataAsetTetap['beban_per_bulan'];
                $data["nilai_penyusutan"] = $data->DataAsetTetap['nilai_penyusutan'];
            } */

            return Response::HttpResponse(200, $datas, "Detail", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Index(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'page' => 'numeric',
                'limit' => 'numeric',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            $datas = DataAsetTetap::with("AkunMesindanKendaraan","AkunGedungdanBangunan","AkunTanah")->paginate($request->limit);
            foreach ($datas as $d_key => $data) {
                /* $data["nominal"] = empty($data["ptp"]) ? $data->ptt['saldo'] : $data->ptp['saldo'];
                $data["tanggal_transaksi"] = empty($data["ptp"]) ? $data->ptt['tanggal_transaksi'] : $data->ptp['tanggal_transaksi']; */
                
                $data["tanggal_transaksi"] = null;
                $data["nominal"] = null;

                if (empty($data["AkunMesindanKendaraan"])){
                    switch (true) {
                        case empty($data["AkunGedungdanBangunan"]):
                            $data["tanggal_transaksi"] = $data->AkunTanah['tanggal_transaksi'];
                            $data["nominal"] = $data->AkunTanah['saldo'];
                            break;

                        case empty($data["AkunTanah"]):
                            $data["tanggal_transaksi"] = $data->AkunGedungdanBangunan['tanggal_transaksi'];
                            $data["nominal"] = $data->AkunGedungdanBangunan['saldo'];
                        break;
                
                        default:
                            
                        break;
                    }
                }else{
                    $data["tanggal_transaksi"] = $data->AkunMesindanKendaraan['tanggal_transaksi'];
                    $data["nominal"] = $data->AkunMesindanKendaraan['saldo'];
                }
            }

            return Response::HttpResponse(200, $datas, "Index", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Edit(Request $request, $id)
    {
        try 
        {
            $dataAsetTetap = DataAsetTetap::find($id)
            ->makeHidden(['created_at','updated_at','deleted_at','created_by','modified_by','deleted_by']);
            return Response::HttpResponse(200, $dataAsetTetap, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Update(Request $request,$id)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_aset' => 'required|string|max:255',
                'kelompok' => 'required|in:persediaan,kendaraan,gedung,tanah,peralatan,asetlain',
                'tanggal_beli' => 'required|date_format:Y-m-d',
                'harga_perolehan' => 'required|numeric',
                'nilai_bersih' => 'required|numeric',
                'nilai_residu' => 'required|numeric',
                'umur_ekonomis' => 'required|numeric',
                'lokasi' => 'required|string|max:255',

                //akumulasi
                'nomor' => 'required|numeric',
                'departemen' => 'required|string|max:255',
                'akumulasi_beban' => 'required|numeric',
                'beban_per_tahun_ini' => 'required|numeric',
                'terhitung_tanggal' => 'required|date_format:Y-m-d',
                'nilai_buku' => 'required|numeric',
                'beban_per_bulan' => 'required|numeric',
                'nilai_penyusutan' => 'required|numeric',

                'keterangan' => 'required|string|max:255',
                'tanggal_transaksi' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataAsetTetap = DataAsetTetap::find($id);

            $dataAsetTetap->nama_aset = $request->nama_aset;
            $dataAsetTetap->kelompok = $request->kelompok;
            $dataAsetTetap->tanggal_beli = $request->tanggal_beli;
            $dataAsetTetap->harga_perolehan = $request->harga_perolehan;
            $dataAsetTetap->nilai_residu = $request->nilai_residu;
            $dataAsetTetap->nilai_bersih = $request->nilai_bersih;
            $dataAsetTetap->harga_perolehan = $request->harga_perolehan;
            $dataAsetTetap->lokasi = $request->lokasi;
            $dataAsetTetap->nomor = $request->nomor;
            $dataAsetTetap->departemen = $request->departemen;
            $dataAsetTetap->akumulasi_beban = $request->akumulasi_beban;
            $dataAsetTetap->beban_per_tahun_ini = $request->beban_per_tahun_ini;
            $dataAsetTetap->terhitung_tanggal = $request->terhitung_tanggal;
            $dataAsetTetap->nilai_buku = $request->nilai_buku;
            $dataAsetTetap->beban_per_bulan = $request->beban_per_bulan;
            $dataAsetTetap->nilai_penyusutan = $request->nilai_penyusutan;
            $dataAsetTetap->created_by = $this->admin->name;
            $dataAsetTetap->modified_by = $this->admin->name;

            $newDataAsetTetap = $dataAsetTetap->save();

            if (!$newDataAsetTetap) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $kelompok = $dataAsetTetap->kelompok;

            switch ($kelompok) {
                case "kendaraan":
                    $newKendaraan = new AkunMesindanKendaraan();
                    $newKendaraan->tanggal_transaksi = $request->tanggal_transaksi;
                    $newKendaraan->keterangan = $request->keterangan;
                    $newKendaraan->saldo = $dataAsetTetap->harga_perolehan;
                    $newKendaraan->type = 'debit';
                    $newKendaraan->data_aset_tetap_id = $dataAsetTetap->id;
                    $newKendaraan = $newKendaraan->save();

                    if (!$newKendaraan) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "gedung":
                    $newGedung = new AkunGedungdanBangunan();
                    $newGedung->tanggal_transaksi = $request->tanggal_transaksi;
                    $newGedung->keterangan = $request->keterangan;
                    $newGedung->saldo = $dataAsetTetap->harga_perolehan;
                    $newGedung->type = 'debit';
                    $newGedung->data_aset_tetap_id = $dataAsetTetap->id;
                    $newGedung = $newGedung->save();

                    if (!$newGedung) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;

            case "tanah":
                    $newTanah = new AkunTanah();
                    $newTanah->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTanah->keterangan = $request->keterangan;
                    $newTanah->saldo = $dataAsetTetap->harga_perolehan;
                    $newTanah->type = 'debit';
                    $newTanah->data_aset_tetap_id = $dataAsetTetap->id;
                    $newTanah = $newTanah->save();

                    if (!$newTanah) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

            break;    

            case "peralatan":
                $newPeralatan = new AkunPeralatandanPerlengkapanKantor();
                $newPeralatan->tanggal_transaksi = $request->tanggal_transaksi;
                $newPeralatan->keterangan = $request->keterangan;
                $newPeralatan->saldo = $dataAsetTetap->harga_perolehan;
                $newPeralatan->type = 'debit';
                $newPeralatan->data_aset_tetap_id = $dataAsetTetap->id;
                $newPeralatan = $newPeralatan->save();

                if (!$newPeralatan) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

           break;   

            case "persediaan":
                $newPersediaan = new AkunPersediaan();
                $newPersediaan->tanggal_transaksi = $request->tanggal_transaksi;
                $newPersediaan->keterangan = $request->keterangan;
                $newPersediaan->saldo = $dataAsetTetap->harga_perolehan;
                $newPersediaan->type = 'debit';
                $newPersediaan->data_aset_tetap_id = $dataAsetTetap->id;
                $newPersediaan = $newPersediaan->save();

                if (!$newPersediaan) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            break;    

            case "asetlain":
                $newAsetLain = new AkunAsetLainLain();
                $newAsetLain->tanggal_transaksi = $request->tanggal_transaksi;
                $newAsetLain->keterangan = $request->keterangan;
                $newAsetLain->saldo = $dataAsetTetap->harga_perolehan;
                $newAsetLain->type = 'debit';
                $newAsetLain->data_aset_tetap_id = $dataAsetTetap->id;
                $newAsetLain = $newAsetLain->save();

                if (!$newAsetLain) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            break;   
                
                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newDataAsetTetap, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Delete(Request $request,int $id) {
        try {
            $currData = DataAsetTetap::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->name;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function DropdownKelompok(Request $request){
        $kelompok=['persediaan'=>'Persediaan','kendaraan'=>'Kendaraan','gedung'=>'Gedung','tanah'=>'Tanah','peralatan'=>'Peralatan dan Perlengkapan Kantor','asetlain'=>'Aset Lain Lain'];

        return Response::HttpResponse(200, $kelompok, "Success", true);
    }

    public function Search(Request $request) 
    {

        $inputs =  $request->all();
        $validator = Validator::make($inputs, [
            'search_type' => 'min:1',
            'value' => 'min:1',
        ]);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()->all()];
            return Response::HttpResponse(422, $response, "Invalid Data", false);
        }

        $datas = DataAsetTetap::where($inputs["search_type"],"like","%".$inputs["value"]."%")->paginate(10);
        return Response::HttpResponse(200, $datas, "OK", false);
    }
}