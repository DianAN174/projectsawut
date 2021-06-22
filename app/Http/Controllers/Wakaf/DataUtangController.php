<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\ModelPengelolaanLain\DataUtang;
use App\Models\ModelPengelolaanLain\UtangBiaya;
use App\Models\ModelPengelolaanLain\UtangJangkaPanjang;

use App\Models\User;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class DataUtangController
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
                'kategori_utang' => 'required|in:biaya,jangkapanjang',
                'nominal' => 'required|numeric',
                'keterangan_utang' => 'required|string|max:255',

                /* 'keterangan' => 'required|string|max:255',
                'tanggal_transaksi' => 'required|date_format:Y-m-d', */
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataUtang = new DataUtang();

            $dataUtang->kategori_utang = $request->kategori_utang;
            $dataUtang->nominal = $request->nominal;
            $dataUtang->keterangan_utang = $request->keterangan_utang;
            $dataUtang->created_by = $this->admin->nama_pengguna;
            $dataUtang->modified_by = $this->admin->nama_pengguna;

            $newDataUtang = $dataUtang->save();

            if (!$newDataUtang) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $kategoriUtang = $dataUtang->kategori_utang;

                switch ($kategoriUtang) {
                    case "biaya":
                        $newUtangBiaya = new UtangBiaya();
                        $newUtangBiaya->tanggal_transaksi = $request->tanggal_transaksi;
                        $newUtangBiaya->keterangan = $request->keterangan;
                        $newUtangBiaya->saldo = $request->nominal;
                        $newUtangBiaya->type = 'pemasukan';
                        $newUtangBiaya->data_utang_id = $dataUtang->id;
                        $newUtangBiaya = $newUtangBiaya->save();
    
                        if (!$newUtangBiaya) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
    
                        break;
    
                    case "jangkapanjang":
                        $newUtangJangkaPanjang = new UtangJangkaPanjang();
                        //$newUtangJangkaPanjang->tanggal_transaksi = $request->tanggal_transaksi;
                        //$newUtangJangkaPanjang->keterangan = $request->keterangan;
                        $newUtangJangkaPanjang->saldo = $request->nominal;
                        $newUtangJangkaPanjang->type = 'pemasukan';
                        $newUtangJangkaPanjang->data_utang_id = $dataUtang->id;
                        $newUtangJangkaPanjang = $newUtangJangkaPanjang->save();
    
                        if (!$newUtangJangkaPanjang) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
    
                    break;
                    default:
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                }

            DB::commit();

            return Response::HttpResponse(200, $newDataUtang, "Success", false);
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

            $datas = DataUtang::with("UtangBiaya","UtangJangkaPanjang")->paginate($request->limit);
            //->makeHidden(['created_at','updated_at','deleted_at','created_by','modified_by','deleted_by']);
            foreach ($datas as $d_key => $data) {
                if ($data["kategori_utang"] == 'biaya'){
                    $data["kategori_utang"] = (string)'Utang Biaya';

                }else{
                    
                    $data["kategori_utang"] = (string)'Utang Jangka Panjang';
                }
            }

            /* foreach ($datas as $d_key => $data) {
                $data["nominal"] = empty($data["UtangBiaya"]) ? $data->UtangJangkaPanjang['saldo'] : $data->UtangBiaya['saldo'];
                $data["tanggal_transaksi"] = empty($data["UtangBiaya"]) ? $data->UtangJangkaPanjang['tanggal_transaksi'] : $data->UtangBiaya['tanggal_transaksi']; 
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
            $datas = DataUtang::select('kategori_utang','nominal','keterangan_utang')
            ->where('id',$id)->get();
            //$datas = DataUtang::find($id);
            /* foreach ($datas as $d_key => $data) {
                if ($data["kategori_utang"] == 'biaya'){
                    $data["kategori_utang"] = (string)'Utang Biaya';

                }else{
                    
                    $data["kategori_utang"] = (string)'Utang Jangka Panjang';
                }
            } */
            
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
                'kategori_utang' => 'required|in:biaya,jangkapanjang',
                'nominal' => 'required|numeric',
                'keterangan_utang' => 'required|string|max:255',
                /* 'keterangan' => 'required|string|max:255',
                'tanggal_transaksi' => 'required|date_format:Y-m-d', */
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $dataUtang = DataUtang::find($id);

            $dataUtang->kategori_utang = $request->kategori_utang;
            $dataUtang->nominal = $request->nominal;
            $dataUtang->keterangan_utang = $request->keterangan_utang;
            $dataUtang->created_by = $this->admin->nama_pengguna;
            $dataUtang->modified_by = $this->admin->nama_pengguna;

            $newDataUtang = $dataUtang->save();

            if (!$newDataUtang) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $kategoriUtang = $dataUtang->kategori_utang;

                switch ($kategoriUtang) {
                    case "biaya":
                        $newUtangBiaya = new UtangBiaya();
                        $newUtangBiaya->tanggal_transaksi = $request->tanggal_transaksi;
                        $newUtangBiaya->keterangan = $request->keterangan;
                        $newUtangBiaya->saldo = $request->nominal;
                        $newUtangBiaya->type = 'pemasukan';
                        $newUtangBiaya->data_utang_id = $dataUtang->id;
                        $newUtangBiaya = $newUtangBiaya->save();
    
                        if (!$newUtangBiaya) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
    
                        break;
    
                    case "jangkapanjang":
                        $newUtangJangkaPanjang = new UtangJangkaPanjang();
                        //$newUtangJangkaPanjang->tanggal_transaksi = $request->tanggal_transaksi;
                        //$newUtangJangkaPanjang->keterangan = $request->keterangan;
                        $newUtangJangkaPanjang->saldo = $request->nominal;
                        $newUtangJangkaPanjang->type = 'pemasukan';
                        $newUtangJangkaPanjang->data_utang_id = $dataUtang->id;
                        $newUtangJangkaPanjang = $newUtangJangkaPanjang->save();
    
                        if (!$newUtangJangkaPanjang) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
    
                    break;
                    default:
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                }

            DB::commit();

            return Response::HttpResponse(200, $newDataUtang, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }
    
    public function Delete(Request $request,int $id) {
        try {
            $currData = DataUtang::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->nama_pengguna;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function DropdownKategori(Request $request){
        $kategori=['biaya'=>'Utang Biaya','jangkapanjang'=>'Utang Jangka Panjang'];

        return Response::HttpResponse(200, $kategori, "Success", true);
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

        $datas = DataUtang::where($inputs["search_type"],"like","%".$inputs["value"]."%")->paginate(10);
        return Response::HttpResponse(200, $datas, "OK", false);
    }

    /* public function Persetujuan(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();
            $dataUtang = DataUtang::find($id);

            $approval = $dataUtang->approval;
            
            DB::beginTransaction();

            if($approval==0)
            {
                $dataUtang->approval = 1;
                //$dataUtang->status_persetujuan = 'approved';
                
                $dataUtang->approved_at = \Carbon\Carbon::now();
                $dataUtang->approved_by = $this->admin->nama_pengguna;
                
            }

            $newDataUtang = $dataUtang->save();

                if (!$newDataUtang) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

                $kategoriUtang = $dataUtang->kategori_utang;

                switch ($kategoriUtang) {
                    case "biaya":
                        $newUtangBiaya = new UtangBiaya();
                        //$newUtangBiaya->tanggal_transaksi = $request->tanggal_transaksi;
                        //$newUtangBiaya->keterangan = $request->keterangan;
                        $newUtangBiaya->saldo = $dataUtang->nominal;
                        $newUtangBiaya->type = 'pemasukan';
                        $newUtangBiaya->data_utang_id = $dataUtang->id;
                        $newUtangBiaya = $newUtangBiaya->save();
    
                        if (!$newUtangBiaya) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
    
                        break;
    
                    case "jangkapanjang":
                        $newUtangJangkaPanjang = new UtangJangkaPanjang();
                        //$newUtangJangkaPanjang->tanggal_transaksi = $request->tanggal_transaksi;
                        //$newUtangJangkaPanjang->keterangan = $request->keterangan;
                        $newUtangJangkaPanjang->saldo = $dataUtang->nominal;
                        $newUtangJangkaPanjang->type = 'pemasukan';
                        $newUtangJangkaPanjang->data_utang_id = $dataUtang->id;
                        $newUtangJangkaPanjang = $newUtangJangkaPanjang->save();
    
                        if (!$newUtangJangkaPanjang) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
    
                    break;
                    default:
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                }
    
            DB::commit();

            return Response::HttpResponse(200, $newDataUtang, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }

    } */
    
}