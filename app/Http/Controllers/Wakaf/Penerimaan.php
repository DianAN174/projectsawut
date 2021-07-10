<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\DataWakif;
use App\Models\PenerimaanTunaiPermanen;
use App\Models\WakafTemporerJangkaPendek;
use App\Models\WakafTemporerJangkaPanjang;
use App\Models\ModelPengelolaan\KasTunai;
use App\Models\User;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Mockery\Exception;


Class Penerimaan
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
                'tanggal_transaksi' => 'required|date_format:Y-m-d',
                'nama_wakif' => 'required|string|max:255',
                'nik' => 'required|numeric',
                'nomor_aiw' => 'required|numeric|unique:data_wakif,nomor_aiw',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|numeric',
                'jenis_wakaf' => 'required|in:temporer,permanen',
                'jangka_waktu_temporer' => 'required|numeric',
                'nominal' => 'required|numeric',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                //'keterangan' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataWakif = new DataWakif();

            $dataWakif->tanggal_transaksi = $request->tanggal_transaksi;
            $dataWakif->nama_wakif = $request->nama_wakif;
            $dataWakif->nik = $request->nik;
            $dataWakif->nomor_aiw = $request->nomor_aiw;
            $dataWakif->alamat = $request->alamat;
            $dataWakif->telepon = $request->telepon;
            $dataWakif->jenis_wakaf = $request->jenis_wakaf;
            $dataWakif->jangka_waktu_temporer = $request->jangka_waktu_temporer;
            $dataWakif->nominal = $request->nominal;
            $dataWakif->metode_pembayaran = $request->metode_pembayaran;
            $dataWakif->created_by = $this->admin->nama_pengguna;
            $dataWakif->modified_by = $this->admin->nama_pengguna;

            $newDataWakif = $dataWakif->save();

            if (!$newDataWakif) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $jenisWakaf = $dataWakif->jenis_wakaf;
            $jangkaWaktu = $dataWakif->jangka_waktu_temporer;

            switch ($jenisWakaf) {
                case "permanen":
                    $newPTP = new PenerimaanTunaiPermanen();
                    $newPTP->tanggal_transaksi = $request->tanggal_transaksi;
                    $newPTP->keterangan = $request->keterangan;
                    $newPTP->saldo = $request->nominal;
                    $newPTP->type = 'pemasukan';
                    $newPTP->data_wakif_id = $dataWakif->id;
                    $newPTP = $newPTP->save();

                    if (!$newPTP) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }

                    $newTunai = new KasTunai();
                    $newTunai->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTunai->keterangan = $request->keterangan;
                    $newTunai->saldo = $request->nominal;
                    $newTunai->type = 'pemasukan';
                    $newTunai->data_wakif_id = $dataWakif->id;
                    $newTunai = $newTunai->save();

                    if (!$newTunai) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }

                    break;
                case "temporer":
                    if($jangkaWaktu < 12)
                    {
                        $newWTPD = new WakafTemporerJangkaPendek();
                        $newWTPD->tanggal_transaksi = $request->tanggal_transaksi;
                        $newWTPD->keterangan = $request->keterangan;
                        $newWTPD->saldo = $request->nominal;
                        $newWTPD->type = 'pemasukan';
                        $newWTPD->data_wakif_id = $dataWakif->id;
                        $newWTPD = $newWTPD->save();

                        if (!$newWTPD) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                        }
                    }else{
                        $newWTPJ = new WakafTemporerJangkaPanjang();
                        $newWTPJ->tanggal_transaksi = $request->tanggal_transaksi;
                        $newWTPJ->keterangan = $request->keterangan;
                        $newWTPJ->saldo = $request->nominal;
                        $newWTPJ->type = 'pemasukan';
                        $newWTPJ->data_wakif_id = $dataWakif->id;
                        $newWTPJ = $newWTPJ->save();

                        if (!$newWTPJ) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                        }

                        $newTunai = new KasTunai();
                        $newTunai->tanggal_transaksi = $request->tanggal_transaksi;
                        $newTunai->keterangan = $request->keterangan;
                        $newTunai->saldo = $request->nominal;
                        $newTunai->type = 'pemasukan';
                        $newTunai->data_wakif_id = $dataWakif->id;
                        $newTunai = $newTunai->save();
    
                        if (!$newTunai) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                        }

                    }
                    

                    break;
                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newDataWakif, "Success", false);
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

            $datas = DataWakif::with("ptp","wtpd","wtpj")->paginate($request->limit);
            /* foreach ($datas as $d_key => $data) {
                $data["tanggal_transaksi"] = null;
                $data["nominal"] = null;

                if (empty($data["ptp"])){
                    switch (true) {
                        case empty($data["wtpd"]):
                            $data["tanggal_transaksi"] = $data->wtpj['tanggal_transaksi'];
                            $data["nominal"] = $data->wtpj['saldo'];
                            break;

                        case empty($data["wtpj"]):
                            $data["tanggal_transaksi"] = $data->wtpd['tanggal_transaksi'];
                            $data["nominal"] = $data->wtpd['saldo'];
                        break;
                
                        default:
                            
                        break;
                    }
                }else{
                    $data["tanggal_transaksi"] = $data->ptp['tanggal_transaksi'];
                    $data["nominal"] = $data->ptp['saldo'];
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
            $dataWakif = DataWakif::select('tanggal_transaksi','nama_wakif','nik','nomor_aiw','alamat','telepon','jenis_wakaf','jangka_waktu_temporer','nominal','metode_pembayaran')
            ->where('id',$id)->get();
            return Response::HttpResponse(200, $dataWakif, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Update(Request $request, $id)
    {
        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'tanggal_transaksi' => 'required|date_format:Y-m-d',
                'nama_wakif' => 'required|string|max:255',
                'nik' => 'required|numeric',
                'nomor_aiw' => ['required','numeric',Rule::unique('data_wakif','nomor_aiw')->ignore($id)],
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|numeric',
                'jenis_wakaf' => 'required|in:temporer,permanen',
                'jangka_waktu_temporer' => 'required|numeric',
                'nominal' => 'required|numeric',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                //'keterangan' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataWakif=DataWakif::find($id);
            
            //data sblm diedit
            $jenisWakaf = $dataWakif->jenis_wakaf;
            $jangkaWaktu = $dataWakif->jangka_waktu_temporer;

            //untuk mengetahui apakah user mengupdate jenis wakaf atau tidak
            
                switch ($request->jenis_wakaf) {
                    case "permanen":
                    if($jenisWakaf == $request->jenis_wakaf)
                    {
                        $newPTP = PenerimaanTunaiPermanen::where('data_wakif_id',$dataWakif->id)->first('id');
                        $newPTP->modified_by = $this->admin->nama_pengguna;
                    }
                    else{
                        $newPTP = new PenerimaanTunaiPermanen();
                        $newPTP->created_by = $this->admin->nama_pengguna;
                    }

                        $newPTP->tanggal_transaksi = $request->tanggal_transaksi;
                        $newPTP->keterangan = $request->keterangan;
                        $newPTP->saldo = $request->nominal;
                        $newPTP->type = 'pemasukan';
                        $newPTP->data_wakif_id = $dataWakif->id;
                        $newPTP = $newPTP->save();
                        
    
                        if (!$newPTP) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                        }
                        break;
                        //buat fungsi delete di bawah

                    case "temporer":
                        if(($jangkaWaktu < 12) && ($request->jangka_waktu_temporer < 12) && ($jenisWakaf == $request->jenis_wakaf))
                        {
                            $newWTPD = WakafTemporerJangkaPendek::where('data_wakif_id',$dataWakif->id)->first('id');
                            $newWTPD->modified_by = $this->admin->nama_pengguna;
                            
                        }
                        else{
                            $newWTPD = new WakafTemporerJangkaPendek();
                            $newWTPD->created_by = $this->admin->nama_pengguna;
                        }
                            
                            $newWTPD->tanggal_transaksi = $request->tanggal_transaksi;
                            $newWTPD->keterangan = $request->keterangan;
                            $newWTPD->saldo = $request->nominal;
                            $newWTPD->type = 'pemasukan';
                            $newWTPD->data_wakif_id = $dataWakif->id;
                            $newWTPD = $newWTPD->save();
    
                            if (!$newWTPD) {
                                DB::rollBack();
                                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                            }

                        if(($jangkaWaktu > 12) && ($request->jangka_waktu_temporer > 12) && ($jenisWakaf == $request->jenis_wakaf))
                        {
                            $newWTPJ = WakafTemporerJangkaPanjang::where('data_wakif_id',$dataWakif->id)->first('id');
                            $newWTPJ->modified_by = $this->admin->nama_pengguna;
                        }
                        else{    
                            $newWTPJ = new WakafTemporerJangkaPanjang();
                            $newWTPJ->created_by = $this->admin->nama_pengguna;
                        }
                            $newWTPJ->tanggal_transaksi = $request->tanggal_transaksi;
                            $newWTPJ->keterangan = $request->keterangan;
                            $newWTPJ->saldo = $request->nominal;
                            $newWTPJ->type = 'pemasukan';
                            $newWTPJ->data_wakif_id = $dataWakif->id;
                            $newWTPJ = $newWTPJ->save();
    
                            if (!$newWTPJ) {
                                DB::rollBack();
                                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                            }
    
    
                        break;
                    default:
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                }

                //delete transaksi yg diedit / pindah akun
                if($jenisWakaf !== $request->jenis_wakaf)
                {
                    switch ($jenisWakaf) {
                    case "permanen":
                        $newPTP = PenerimaanTunaiPermanen::where('data_wakif_id',$dataWakif->id)->first('id');
                        $newPTP->deleted_at = \Carbon\Carbon::now();
                        $newPTP->deleted_by = $this->admin->nama_pengguna;
                        $newPTP = $newPTP->save();
                        
                        if (!$newPTP) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                        }
                        break;

                        case "temporer":
                            if(!($jangkaWaktu < 12) && ($request->jangka_waktu_temporer < 12))
                            {
                                $newWTPD = WakafTemporerJangkaPendek::where('data_wakif_id',$dataWakif->id)->first('id');
                                $newWTPD->deleted_at = \Carbon\Carbon::now();
                                $newWTPD->deleted_by = $this->admin->nama_pengguna;
                                $newWTPD = $newWTPD->save();

                                if (!$newWTPD) {
                                    DB::rollBack();
                                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                                }
                            }
       
    
                            if(!($jangkaWaktu > 12) && ($request->jangka_waktu_temporer > 12))
                            {
                                $newWTPJ = WakafTemporerJangkaPanjang::where('data_wakif_id',$dataWakif->id)->first('id');
                                $newWTPJ->deleted_at = \Carbon\Carbon::now();
                                $newWTPJ->deleted_by = $this->admin->nama_pengguna;
                                $newWTPJ = $newWTPJ->save();

                                if (!$newWTPJ) {
                                    DB::rollBack();
                                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                                }
                            }
        
                                
        
        
                            break;
                        default:
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                }
                //
                $newTunai = KasTunai::where('data_wakif_id',$dataWakif->id)->first('id');
                $newTunai->tanggal_transaksi = $request->tanggal_transaksi;
                $newTunai->keterangan = $request->keterangan;
                $newTunai->saldo = $request->nominal;
                $newTunai->type = 'pemasukan';
                //$newTunai->data_wakif_id = $dataWakif->id;
                $newTunai = $newTunai->save();

                if (!$newTunai) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                }
            
            $dataWakif->tanggal_transaksi = $request->tanggal_transaksi;
            $dataWakif->nama_wakif = $request->nama_wakif;
            $dataWakif->nik = $request->nik;
            $dataWakif->nomor_aiw = $request->nomor_aiw;
            $dataWakif->alamat = $request->alamat;
            $dataWakif->telepon = $request->telepon;
            $dataWakif->jenis_wakaf = $request->jenis_wakaf;
            $dataWakif->jangka_waktu_temporer = $request->jangka_waktu_temporer;
            $dataWakif->nominal = $request->nominal;
            $dataWakif->metode_pembayaran = $request->metode_pembayaran;
            $dataWakif->created_by = $this->admin->nama_pengguna;
            $dataWakif->modified_by = $this->admin->nama_pengguna;
            $newDataWakif = $dataWakif->save();
            
            if (!$newDataWakif) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newDataWakif, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Delete(Request $request,int $id) {
        try {
            $currData = DataWakif::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->nama_pengguna;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function DropdownJenisWakaf(Request $request){
        $jenis=['permanen'=>'Wakaf Permanen','temporer'=>'Wakaf Temporer'];

        return Response::HttpResponse(200, $jenis, "Success", true);
    }

    public function Search(Request $request) {

        $inputs =  $request->all();
        $validator = Validator::make($inputs, [
            'search_type' => 'min:1',
            'value' => 'min:1',
        ]);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()->all()];
            return Response::HttpResponse(422, $response, "Invalid Data", false);
        }

        $datas = DataWakif::where($inputs["search_type"],"like","%".$inputs["value"]."%")->paginate(10); 
        
        /* $inputs =  $request->all();
        $validator = Validator::make($inputs, [
            'value' => 'min:1',
        ]);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()->all()];
            return Response::HttpResponse(422, $response, "Invalid Data", false);
        }

        $datas = DataWakif::where('nama_wakif',"like","%".$inputs["value"]."%")->paginate(10);  */
        
        return Response::HttpResponse(200, $datas, "OK", false);
    }
}
