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
                'nama' => 'required|string|max:255',
                'nik' => 'required|numeric',
                'nomor_aiw' => 'required|numeric',
                'alamat' => 'required|string|max:255',
                'phone' => 'required|numeric',
                'jenis_wakaf' => 'required|in:temporer,permanen',
                'jangka_temporer' => 'required|numeric',
                'nominal' => 'required|numeric',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                //'keterangan' => 'required',
                //'tanggal_transaksi' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataWakif = new DataWakif();

            $dataWakif->nama_wakif = $request->nama;
            $dataWakif->nik = $request->nik;
            $dataWakif->no_aiw = $request->nomor_aiw;
            $dataWakif->alamat = $request->alamat;
            $dataWakif->telepon = $request->phone;
            $dataWakif->jenis_wakaf = $request->jenis_wakaf;
            $dataWakif->jangka_waktu_temporer = $request->jangka_temporer;
            $dataWakif->nominal = $request->nominal;
            $dataWakif->metode_pembayaran = $request->metode_pembayaran;
            $dataWakif->created_by = $this->admin->name;
            $dataWakif->modified_by = $this->admin->name;

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
                    if($jangkaWaktu <1)
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
            foreach ($datas as $d_key => $data) {
                /* $data["nominal"] = empty($data["ptp"]) ? $data->ptt['saldo'] : $data->ptp['saldo'];
                $data["tanggal_transaksi"] = empty($data["ptp"]) ? $data->ptt['tanggal_transaksi'] : $data->ptp['tanggal_transaksi']; */
                
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
            $dataWakif = DataWakif::select('nama_wakif','nik','no_aiw','alamat','telepon','jenis_wakaf','jangka_waktu_temporer','metode_pembayaran')
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
                'nama' => 'required|string|max:255',
                'nik' => 'required|numeric',
                'nomor_aiw' => 'required|numeric',
                'alamat' => 'required|string|max:255',
                'phone' => 'required|numeric',
                'jenis_wakaf' => 'required|in:temporer,permanen',
                'jangka_temporer' => 'required|numeric',
                'nominal' => 'required|numeric',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                //'keterangan' => 'required',
                //'tanggal_transaksi' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataWakif=DataWakif::find($id);
            
            $dataWakif->nama_wakif = $request->nama;
            $dataWakif->nik = $request->nik;
            $dataWakif->no_aiw = $request->nomor_aiw;
            $dataWakif->alamat = $request->alamat;
            $dataWakif->telepon = $request->phone;
            $dataWakif->jenis_wakaf = $request->jenis_wakaf;
            $dataWakif->jangka_waktu_temporer = $request->jangka_temporer;
            $dataWakif->nominal = $request->nominal;
            $dataWakif->metode_pembayaran = $request->metode_pembayaran;
            $dataWakif->created_by = $this->admin->name;
            $dataWakif->modified_by = $this->admin->name;

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
                    if($jangkaWaktu <1)
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

    public function Delete(Request $request,int $id) {
        try {
            $currData = DataWakif::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->name;

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
        return Response::HttpResponse(200, $datas, "OK", false);
    }
}
