<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\ModelPengelolaanLain\DataAsetTetap;
use App\Models\ModelPengelolaanLain\DataUtang;
//use App\Models\ModelPengelolaanLain\AkunPersediaan;
use App\Models\ModelPengelolaanLain\AkunMesindanKendaraan;
use App\Models\ModelPengelolaanLain\AkunGedungdanBangunan;
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
                //'kelompok' => 'required|in:persediaan,kendaraan,gedung,tanah,peralatan,lainnya',
                'kelompok' => 'required|in:kendaraan,gedung,tanah,peralatan,lainnya',
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

                'tanggal_transaksi' => 'nullable|date_format:Y-m-d',
                /* 'keterangan' => 'required|string|max:255', */
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            if($request->tanggal_transaksi==null){
                $request->tanggal_transaksi = \Carbon\Carbon::now();
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
            $dataAsetTetap->umur_ekonomis = $request->umur_ekonomis;
            $dataAsetTetap->lokasi = $request->lokasi;
            $dataAsetTetap->nomor = $request->nomor;
            $dataAsetTetap->departemen = $request->departemen;
            $dataAsetTetap->akumulasi_beban = $request->akumulasi_beban;
            $dataAsetTetap->beban_per_tahun_ini = $request->beban_per_tahun_ini;
            $dataAsetTetap->terhitung_tanggal = $request->terhitung_tanggal;
            $dataAsetTetap->nilai_buku = $request->nilai_buku;
            $dataAsetTetap->beban_per_bulan = $request->beban_per_bulan;
            $dataAsetTetap->nilai_penyusutan = $request->nilai_penyusutan;
            $dataAsetTetap->created_by = $this->admin->nama_pengguna;
            $dataAsetTetap->modified_by = $this->admin->nama_pengguna;

            $newDataAsetTetap = $dataAsetTetap->save();

            if (!$newDataAsetTetap) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data", true);
            }

            $kelompok = $dataAsetTetap->kelompok;
            
            switch ($kelompok) {
                case "kendaraan":
                    $newKendaraan = new AkunMesindanKendaraan();
                    $newKendaraan->tanggal_transaksi = $request->tanggal_transaksi;
                    $newKendaraan->keterangan = $request->keterangan;
                    $newKendaraan->saldo = $dataAsetTetap->harga_perolehan;
                    $newKendaraan->type = 'pemasukan';
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
                    $newGedung->type = 'pemasukan';
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
                    $newTanah->type = 'pemasukan';
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
                $newPeralatan->type = 'pemasukan';
                $newPeralatan->data_aset_tetap_id = $dataAsetTetap->id;
                $newPeralatan = $newPeralatan->save();

                if (!$newPeralatan) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

           break;  

            case "lainnya":
                $newlainnya = new AkunAsetLainLain();
                $newlainnya->tanggal_transaksi = $request->tanggal_transaksi;
                $newlainnya->keterangan = $request->keterangan;
                $newlainnya->saldo = $dataAsetTetap->harga_perolehan;
                $newlainnya->type = 'pemasukan';
                $newlainnya->data_aset_tetap_id = $dataAsetTetap->id;
                $newlainnya = $newlainnya->save();

                if (!$newlainnya) {
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
            
            $datas = DataAsetTetap::with("AkunMesindanKendaraan","AkunGedungdanBangunan","AkunTanah","AkunPeralatandanPerlengkapanKantor","AkunAsetLainLain")->paginate($request->limit);
            /* foreach ($datas as $d_key => $data) {
                
                if ($data["kelompok"] == 'kendaraan'){
                    $data["kelompok"] = (string) 'Mesin dan Kendaraan';
                }elseif ($data["jenis_usaha"] == 'gedung'){
                    $data["jenis_usaha"] = (string) 'Gedung dan Bangunan';
                }elseif ($data["jenis_usaha"] == 'tanah'){
                    $data["jenis_usaha"] = (string) 'Tanah';
                }elseif ($data["jenis_usaha"] == 'peralatan'){
                    $data["jenis_usaha"] = (string) 'Peralatan dan Perlengkapan Kantor';
                }elseif ($data["jenis_usaha"] == 'lainnya'){
                    $data["jenis_usaha"] = (string) 'Aset Lainnya';
                }
                
            } */

            return Response::HttpResponse(200, $datas, "Index", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Edit(Request $request, $id)
    {
        try 
        {
            $datas = DataAsetTetap::find($id);
            
            $datas = DataAsetTetap::select('nama_aset','kelompok','tanggal_beli','harga_perolehan','nilai_bersih','nilai_residu','umur_ekonomis','lokasi',
            'nomor','departemen','akumulasi_beban','beban_per_tahun_ini','terhitung_tanggal','nilai_buku','beban_per_bulan','nilai_penyusutan')
            ->where('id',$id)->get();
            /* foreach ($datas as $d_key => $data) {
                
                if ($data["kelompok"] == 'kendaraan'){
                    $data["kelompok"] = (string) 'Mesin dan Kendaraan';
                }elseif ($data["jenis_usaha"] == 'gedung'){
                    $data["jenis_usaha"] = (string) 'Gedung dan Bangunan';
                }elseif ($data["jenis_usaha"] == 'tanah'){
                    $data["jenis_usaha"] = (string) 'Tanah';
                }elseif ($data["jenis_usaha"] == 'peralatan'){
                    $data["jenis_usaha"] = (string) 'Peralatan dan Perlengkapan Kantor';
                }elseif ($data["jenis_usaha"] == 'lainnya'){
                    $data["jenis_usaha"] = (string) 'Aset Lainnya';
                }
            } */
            //->makeHidden(['created_at','updated_at','deleted_at','created_by','modified_by','deleted_by']);
            return Response::HttpResponse(200, $datas, "Info User yang akan diedit berhasil ditampilkan", false);
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
                'kelompok' => 'required|in:kendaraan,gedung,tanah,peralatan,lainnya',
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

                'tanggal_transaksi' => 'nullable|date_format:Y-m-d',

                /* 'keterangan' => 'required|string|max:255',
                'tanggal_transaksi' => 'required|date_format:Y-m-d', */
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            if($request->tanggal_transaksi==null){
                $request->tanggal_transaksi = \Carbon\Carbon::now();
            }

            DB::beginTransaction();

            $dataAsetTetap = DataAsetTetap::find($id);
            //data kelompok sebelum diedit
            $kelompok = $dataAsetTetap->kelompok;

            switch ($request->kelompok) {
                case "kendaraan":
                    if($kelompok == $request->kelompok)
                    {
                        $newKendaraan = AkunMesindanKendaraan::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                        $newKendaraan->modified_by = $this->admin->nama_pengguna;
                    }
                    else{
                        $newKendaraan = new AkunMesindanKendaraan();
                        $newKendaraan->created_by = $this->admin->nama_pengguna;
                    }
                    $newKendaraan->tanggal_transaksi = $request->tanggal_transaksi;
                    $newKendaraan->keterangan = $request->keterangan;
                    $newKendaraan->saldo = $dataAsetTetap->harga_perolehan;
                    $newKendaraan->type = 'pemasukan';
                    $newKendaraan->data_aset_tetap_id = $dataAsetTetap->id;
                    $newKendaraan = $newKendaraan->save();

                    if (!$newKendaraan) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "gedung":
                    if($kelompok == $request->kelompok)
                    {
                        $newGedung = AkunGedungdanBangunan::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                        $newGedung->modified_by = $this->admin->nama_pengguna;
                    }
                    else{
                        $newGedung = new AkunGedungdanBangunan();
                        $newGedung->created_by = $this->admin->nama_pengguna;
                    }
                    $newGedung->tanggal_transaksi = $request->tanggal_transaksi;
                    $newGedung->keterangan = $request->keterangan;
                    $newGedung->saldo = $dataAsetTetap->harga_perolehan;
                    $newGedung->type = 'pemasukan';
                    $newGedung->data_aset_tetap_id = $dataAsetTetap->id;
                    $newGedung = $newGedung->save();

                    if (!$newGedung) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;

                case "tanah":
                    if($kelompok == $request->kelompok)
                    {
                        $newTanah = AkunTanah::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                        $newTanah->modified_by = $this->admin->nama_pengguna;
                    }
                    else{
                        $newTanah = new AkunTanah();
                        $newTanah->created_by = $this->admin->nama_pengguna;
                    }
                    $newTanah->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTanah->keterangan = $request->keterangan;
                    $newTanah->saldo = $dataAsetTetap->harga_perolehan;
                    $newTanah->type = 'pemasukan';
                    $newTanah->data_aset_tetap_id = $dataAsetTetap->id;
                    $newTanah = $newTanah->save();

                    if (!$newTanah) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;    

                case "peralatan":
                if($kelompok == $request->kelompok)
                    {
                        $newPeralatan = AkunPeralatandanPerlengkapanKantor::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                        $newPeralatan->modified_by = $this->admin->nama_pengguna;
                    }
                    else{
                        $newPeralatan = new AkunPeralatandanPerlengkapanKantor();
                        $newPeralatan->created_by = $this->admin->nama_pengguna;
                    }
                $newPeralatan = new AkunPeralatandanPerlengkapanKantor();
                $newPeralatan->tanggal_transaksi = $request->tanggal_transaksi;
                $newPeralatan->keterangan = $request->keterangan;
                $newPeralatan->saldo = $dataAsetTetap->harga_perolehan;
                $newPeralatan->type = 'pemasukan';
                $newPeralatan->data_aset_tetap_id = $dataAsetTetap->id;
                $newPeralatan = $newPeralatan->save();

                if (!$newPeralatan) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

                break;   

                case "lainnya":
                if($kelompok == $request->kelompok)
                {
                    $newlainnya = AkunAsetLainLain::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                    $newlainnya->modified_by = $this->admin->nama_pengguna;
                }
                else{
                    $newlainnya = new AkunAsetLainLain();
                    $newlainnya->created_by = $this->admin->nama_pengguna;
                }
                $newlainnya->tanggal_transaksi = $request->tanggal_transaksi;
                $newlainnya->keterangan = $request->keterangan;
                $newlainnya->saldo = $dataAsetTetap->harga_perolehan;
                $newlainnya->type = 'pemasukan';
                $newlainnya->data_aset_tetap_id = $dataAsetTetap->id;
                $newlainnya = $newlainnya->save();

                if (!$newlainnya) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            break;   
                
                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            //delete transaksi yg diedit / pindah akun
            if($kelompok !== $request->kelompok)
            {
                switch ($kelompok) {
                case "kendaraan":
                    $newKendaraan = AkunMesindanKendaraan::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                    $newKendaraan->deleted_at = \Carbon\Carbon::now();
                    $newKendaraan->deleted_by = $this->admin->nama_pengguna;
                    $newKendaraan = $newKendaraan->save();
                        
                    if (!$newKendaraan) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                case "gedung":
                    $newGedung = AkunGedungdanBangunan::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                    $newGedung->deleted_at = \Carbon\Carbon::now();
                    $newGedung->deleted_by = $this->admin->nama_pengguna;
                    $newGedung = $newGedung->save();
                        
                    if (!$newGedung) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;

                case "tanah":
                    $newTanah = AkunTanah::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                    $newTanah->deleted_at = \Carbon\Carbon::now();
                    $newTanah->deleted_by = $this->admin->nama_pengguna;
                    $newTanah = $newTanah->save();
                        
                    if (!$newTanah) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;

                case "peralatan":
                    $newPeralatan = AkunPeralatandanPerlengkapanKantor::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                    $newPeralatan->deleted_at = \Carbon\Carbon::now();
                    $newPeralatan->deleted_by = $this->admin->nama_pengguna;
                    $newPeralatan = $newPeralatan->save();
                        
                    if (!$newPeralatan) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                
                case "lainnya":
                    $newlainnya = AkunAsetLainLain::where('data_aset_tetap_id',$dataAsetTetap->id)->first('id');
                    $newlainnya->deleted_at = \Carbon\Carbon::now();
                    $newlainnya->deleted_by = $this->admin->nama_pengguna;
                    $newlainnya = $newlainnya->save();
                        
                    if (!$newlainnya) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                }
            }

            $dataAsetTetap->nama_aset = $request->nama_aset;
            $dataAsetTetap->kelompok = $request->kelompok;
            $dataAsetTetap->tanggal_beli = $request->tanggal_beli;
            $dataAsetTetap->harga_perolehan = $request->harga_perolehan;
            $dataAsetTetap->nilai_residu = $request->nilai_residu;
            $dataAsetTetap->nilai_bersih = $request->nilai_bersih;
            $dataAsetTetap->harga_perolehan = $request->harga_perolehan;
            $dataAsetTetap->umur_ekonomis = $request->umur_ekonomis;
            $dataAsetTetap->lokasi = $request->lokasi;
            $dataAsetTetap->nomor = $request->nomor;
            $dataAsetTetap->departemen = $request->departemen;
            $dataAsetTetap->akumulasi_beban = $request->akumulasi_beban;
            $dataAsetTetap->beban_per_tahun_ini = $request->beban_per_tahun_ini;
            $dataAsetTetap->terhitung_tanggal = $request->terhitung_tanggal;
            $dataAsetTetap->nilai_buku = $request->nilai_buku;
            $dataAsetTetap->beban_per_bulan = $request->beban_per_bulan;
            $dataAsetTetap->nilai_penyusutan = $request->nilai_penyusutan;
            $dataAsetTetap->created_by = $this->admin->nama_pengguna;
            $dataAsetTetap->modified_by = $this->admin->nama_pengguna;

            $newDataAsetTetap = $dataAsetTetap->save();

            if (!$newDataAsetTetap) {
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

            $currData->deleted_by = $this->admin->nama_pengguna;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function DropdownKelompok(Request $request){
        $kelompok=['kendaraan'=>'Kendaraan','gedung'=>'Gedung','tanah'=>'Tanah','peralatan'=>'Peralatan dan Perlengkapan Kantor','lainnya'=>'Aset Lain Lain'];

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