<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\DataWakif;
use App\Models\PenerimaanTunaiPermanen;
use App\Models\PenerimaanTunaiTemporer;
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
                'nama' => 'required',
                'nik' => 'required',
                'nomer_aiw' => 'required',
                'alamat' => 'required',
                'phone' => 'required',
                'jenis_wakaf' => 'required|in:temporer,permanen',
                'jangka_temporer' => 'required',
                'nominal' => 'required',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                'keterangan' => 'required',
                'tanggal_transaksi' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }


            DB::beginTransaction();

            $dataWakif = new DataWakif();

            $dataWakif->nama_wakif = $request->nama;
            $dataWakif->nik = $request->nik;
            $dataWakif->no_aiw = $request->nomer_aiw;
            $dataWakif->alamat = $request->alamat;
            $dataWakif->telepon = $request->phone;
            $dataWakif->jenis_wakaf = $request->jenis_wakaf;
            $dataWakif->jangka_waktu_temporer = $request->jangka_temporer;
            $dataWakif->metode_pembayaran = $request->metode_pembayaran;
            $dataWakif->created_by = $this->admin->name;
            $dataWakif->modified_by = $this->admin->name;

            $newDataWakif = $dataWakif->save();

            if (!$newDataWakif) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $jenisWakaf = $dataWakif->jenis_wakaf;

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

                    break;
                case "temporer":
                    $newPTT = new PenerimaanTunaiTemporer();
                    $newPTT->tanggal_transaksi = $request->tanggal_transaksi;
                    $newPTT->keterangan = $request->keterangan;
                    $newPTT->saldo = $request->nominal;
                    $newPTT->data_wakif_id = $dataWakif->id;
                    $newPTT = $newPTT->save();

                    if (!$newPTT) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
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

        $validator = Validator::make($request->all(), [
            'page' => 'numeric',
            'limit' => 'numeric',
        ]);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()->all()];
            return Response::HttpResponse(422, $response, "Invalid Data", false);
        }

        $datas = DataWakif::with("ptp","ptt")->paginate($request->limit);
        foreach ($datas as $d_key => $data) {
            $data["nominal"] = empty($data["ptp"]) ? $data->ptt['saldo'] : $data->ptp['saldo'];
            $data["tanggal_transaksi"] = empty($data["ptp"]) ? $data->ptt['tanggal_transaksi'] : $data->ptp['tanggal_transaksi'];
        }

        return Response::HttpResponse(200, $datas, "Index", false);
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
}
