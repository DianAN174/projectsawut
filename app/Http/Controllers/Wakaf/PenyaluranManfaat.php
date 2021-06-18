<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\ModelPenyaluranManfaat\PiutangJangkaPendek;
use App\Models\ModelPenyaluranManfaat\PiutangJangkaPanjang;
use App\Models\ModelPenyaluranManfaat\Penyaluran;
use App\Models\ModelPengelolaan\KasTabBagiHasil;
use App\Models\ModelPengelolaan\KasTabNonbagiHasil;
use App\Models\Kelayakan\PenyaluranTemp;
use App\Models\Kelayakan\Questions;
use App\Models\Kelayakan\Answers;
use App\Models\Kelayakan\TempTable;

use App\Models\User;
use App\Http\Controller\Kelayakan;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class PenyaluranManfaat
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
                'nama_penerima' => 'required|string|max:255',
                'nik' => 'required|numeric|max:255',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|numeric|max:255',
                'jenis_usaha' => 'required|in:perdagangan,fashion,otomotif,kerajinan,it,lainnya',
                'deskripsi_usaha' => 'required|string|max:255',
                'nominal_peminjaman' => 'required|numeric',
                'sumber_biaya' => 'required|in:bagihasil,nonbagihasil',
                //pjp=piutang jangka pendek, pja=piutang jangka panjang
                'jenis_piutang' => 'required|in:pjp,pja',
                'periode_peminjaman' => 'required|numeric',
                'periode_awal' => 'required|date_format:Y-m-d',
                'periode_akhir' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", true);
            }

            DB::beginTransaction();

            $penyaluranBiaya = new Penyaluran();

            $penyaluranBiaya->nama_penerima = $request->nama;
            $penyaluranBiaya->nik = $request->nik;
            $penyaluranBiaya->alamat = $request->alamat;
            $penyaluranBiaya->no_telepon = $request->phone;
            $penyaluranBiaya->jenis_usaha = $request->jenis_usaha;
            $penyaluranBiaya->deskripsi_usaha = $request->deskripsi_usaha;
            $penyaluranBiaya->nominal_peminjaman = $request->nominal;
            $penyaluranBiaya->sumber_biaya = $request->sumber_biaya;
            $penyaluranBiaya->jenis_piutang = $request->jenis_piutang;
            $penyaluranBiaya->periode_peminjaman = $request->periode_peminjaman;
            $penyaluranBiaya->periode_awal = $request->periode_awal;
            $penyaluranBiaya->periode_akhir = $request->periode_akhir;
            $penyaluranBiaya->created_by = $this->admin->name;
            $penyaluranBiaya->modified_by = $this->admin->name; 

            $newPenyaluranBiaya = $penyaluranBiaya->save();

            if (!$newPenyaluranBiaya) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPenyaluranBiaya, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Store(Request $request, $id)
    {

        try {

            $this->admin = $request->user();

            /* $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'nik' => 'required|numeric|max:255',
                'alamat' => 'required|string|max:255',
                'phone' => 'required|numeric|max:255',
                'jenis_usaha' => 'required|in:perdagangan,fashion,otomotif,kerajinan,it,lainnya',
                'deskripsi_usaha' => 'required|string|max:255',
                'nominal' => 'required|numeric',
                'sumber_biaya' => 'required|in:bagihasil,nonbagihasil',
                //pjp=piutang jangka pendek, pja=piutang jangka panjang
                'jenis_piutang' => 'required|in:pjp,pja',
                //dalam bulan atau tahun?
                'periode_peminjaman' => 'required|numeric',
                'periode_awal' => 'required|date_format:Y-m-d',
                'periode_akhir' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", true);
            } */

            DB::beginTransaction();

            $penyaluranTemp = PenyaluranTemp::find($id);
            $penyaluranBiaya = new Penyaluran();

            $penyaluranBiaya->nama_penerima = $penyaluranTemp->nama_penerima;
            $penyaluranBiaya->nik = $penyaluranTemp->nik;
            $penyaluranBiaya->alamat = $penyaluranTemp->alamat;
            $penyaluranBiaya->telepon = $penyaluranTemp->telepon;
            $penyaluranBiaya->jenis_usaha = $penyaluranTemp->jenis_usaha;
            $penyaluranBiaya->deskripsi_usaha = $penyaluranTemp->deskripsi_usaha;
            $penyaluranBiaya->nominal_peminjaman = $penyaluranTemp->nominal_peminjaman;
            $penyaluranBiaya->sumber_biaya = $penyaluranTemp->sumber_biaya;
            $penyaluranBiaya->jenis_piutang = $penyaluranTemp->jenis_piutang;
            $penyaluranBiaya->periode_peminjaman = $penyaluranTemp->periode_peminjaman;
            $penyaluranBiaya->periode_awal = $penyaluranTemp->periode_awal;
            $penyaluranBiaya->periode_akhir = $penyaluranTemp->periode_akhir;
            $penyaluranBiaya->kelayakan = $penyaluranTemp->kelayakan;
            $penyaluranBiaya->created_by = $this->admin->name;
            $penyaluranBiaya->modified_by = $this->admin->name; 

            $newPenyaluranBiaya = $penyaluranBiaya->save();

            if (!$newPenyaluranBiaya) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPenyaluranBiaya, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

   public function ModalCreate(Request $request)
   {
        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_penerima' => 'required|string|max:255',
                'nik' => 'required|numeric|max:255',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|numeric|max:255',
                'jenis_usaha' => 'required|in:perdagangan,fashion,otomotif,kerajinan,it,lainnya',
                'deskripsi_usaha' => 'required|string|max:255',
                'nominal_peminjaman' => 'required|numeric',
                'sumber_biaya' => 'required|in:bagihasil,nonbagihasil',
                //pjp=piutang jangka pendek, pja=piutang jangka panjang
                'jenis_piutang' => 'required|in:pjp,pja',
                'periode_peminjaman' => 'required|numeric',
                'periode_awal' => 'required|date_format:Y-m-d',
                'periode_akhir' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", true);
            }

            //$penyaluranTemp = $request->session()->get('penyaluran_temp');

            DB::beginTransaction();

            $penyaluranTemp = new PenyaluranTemp();

            $penyaluranTemp->nama_penerima = $request->nama_penerima;
            $penyaluranTemp->nik = $request->nik;
            $penyaluranTemp->alamat = $request->alamat;
            $penyaluranTemp->telepon = $request->telepon;
            $penyaluranTemp->jenis_usaha = $request->jenis_usaha;
            $penyaluranTemp->deskripsi_usaha = $request->deskripsi_usaha;
            $penyaluranTemp->nominal_peminjaman = $request->nominal_peminjaman;
            $penyaluranTemp->sumber_biaya = $request->sumber_biaya;
            $penyaluranTemp->jenis_piutang = $request->jenis_piutang;
            $penyaluranTemp->periode_peminjaman = $request->periode_peminjaman;
            $penyaluranTemp->periode_awal = $request->periode_awal;
            $penyaluranTemp->periode_akhir = $request->periode_akhir;
            $penyaluranTemp->created_by = $this->admin->name;
            $penyaluranTemp->modified_by = $this->admin->name; 

            $newPenyaluranTemp = $penyaluranTemp->save();

            if (!$newPenyaluranTemp) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPenyaluranTemp, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function ModalKelayakanFirst(Request $request, $id)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'answer_1' => 'required',
                'answer_2' => 'required',
                'answer_3' => 'required',
                'answer_4' => 'required',
                'answer_5' => 'required',
                'answer_6' => 'required',
                'answer_7' => 'required',
                'answer_8' => 'required',
                'answer_9' => 'required',
                'answer_10' => 'required',
                'answer_11' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", true);
            }

            DB::beginTransaction();

            $penyaluranTemp = PenyaluranTemp::find($id)->id;
            $answers = [$request->answer_1,$request->answer_2,$request->answer_3,$request->answer_4,
            $request->answer_5,$request->answer_6,$request->answer_7,$request->answer_8,$request->answer_9,
            $request->answer_10,$request->answer_11];
            //$answers_array = [];
            //dd($answers[0]);
            for ($i=1; $i<=11; $i++) {
                $answers_array[] = [
                    'penyaluran_temp_id' => $penyaluranTemp,
                    'question_id' => $i,
                    'answer_id' => $answers[$i-1],
                    'created_by' => $this->admin->name,  
                    'modified_by' => $this->admin->name
                ];
            }

        $newKelayakanFirst = TempTable::insert($answers_array); 

            
            /* for ($i=1; $i<=11; $i++) {
                $kelayakanFirst = new TempTable();
                $kelayakanFirst->penyaluran_temp_id = $penyaluranTemp->id;
                $kelayakanFirst->question_id = $i;
                $kelayakanFirst->answer_id = $request->answer;
                
                $kelayakanFirst->created_by = $this->admin->name;
                $kelayakanFirst->modified_by = $this->admin->name;}
            
                
                $newKelayakanFirst = $kelayakanFirst->save(); */
            

            if (!$newKelayakanFirst) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newKelayakanFirst, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function ModalKelayakanSecond(Request $request, $id)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'answer_12' => 'required',
                'answer_13' => 'required',
                'answer_14' => 'required',
                'answer_15' => 'required',
                'answer_16' => 'required',
                'answer_17' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", true);
            }

            DB::beginTransaction();

            $penyaluranTemp = PenyaluranTemp::find($id)->id;
            //$kelayakanSecond = new TempTable();
            $answers = [$request->answer_12,$request->answer_13,$request->answer_14,
            $request->answer_15,$request->answer_16,$request->answer_17];
            for ($i=1; $i<=6; $i++) {
                $answers_array[] = [
                    'penyaluran_temp_id' => $penyaluranTemp,
                    'question_id' => ($i+11),
                    'answer_id' => $answers[$i-1],
                    'created_by' => $this->admin->name,  
                    'modified_by' => $this->admin->name
                ];
            }

            $newKelayakanSecond = TempTable::insert($answers_array);

            if (!$newKelayakanSecond) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data", true);
            }

            DB::commit();

            //return Response::HttpResponse(200, $newKelayakanSecond, "Success", false);

            $skor = TempTable::join("answers","temp_table.answer_id","=","answers.id")
            ->where('temp_table.penyaluran_temp_id',$id)   
            ->sum('answers.score');
            //dd($skor);
                $skor_max = 47;
                $skor_akhir=($skor/$skor_max)*100;

                $penyaluranBiaya = PenyaluranTemp::find($id);
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
                        return Response::HttpResponse(400, null, "Failed to create data", true);
                    }
            return Response::HttpResponse(200, $skor_akhir, "Skor berhasil diproses", false); 

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Persetujuan(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();
            $penyaluranBiaya = Penyaluran::find($id);

            $approval = $penyaluranBiaya->approval;
            
            DB::beginTransaction();

            if($approval==0)
            {
                $penyaluranBiaya->approval = '1';
                $penyaluranBiaya->status_persetujuan = 'approved';
                
                $penyaluranBiaya->approved_at = \Carbon\Carbon::now();
                $penyaluranBiaya->approved_by = $this->admin->name;
                
            }

            $newPenyaluranBiaya = $penyaluranBiaya->save();

                if (!$newPenyaluranBiaya) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            DB::commit();

            return Response::HttpResponse(200, $newPenyaluranBiaya, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }

    }
    
    public function Penyaluran(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();
            $penyaluranBiaya = Penyaluran::find($id);

            $sumberBiaya = $penyaluranBiaya->sumber_biaya;
            $jenisPiutang = $penyaluranBiaya->jenis_piutang;

            $penyaluran = $penyaluranBiaya->penyaluran;
            
            DB::beginTransaction();

            if($penyaluran==0)
            {
                $penyaluranBiaya->penyaluran = '1';
                
            }
            $newPenyaluranBiaya = $penyaluranBiaya->save();

                if (!$newPenyaluranBiaya) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            switch ($sumberBiaya) {
                case "bagihasil":
                        $newBagiHasil = new KasTabBagiHasil();
                        $newBagiHasil->tanggal_transaksi = $penyaluranBiaya->approved_at;
                        $newBagiHasil->keterangan = 'Pencairan Penyaluran Manfaat';
                        $newBagiHasil->saldo = $penyaluranBiaya->nominal_peminjaman;
                        $newBagiHasil->type = 'kredit';
                        $newBagiHasil->penyaluran_id = $penyaluranBiaya->id;
                        $newBagiHasil = $newBagiHasil->save();
        
                        if (!$newBagiHasil) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
        
                        break;
                    case "nonbagihasil":
                        $newNonBagiHasil = new KasTabNonBagiHasil();
                        $newNonBagiHasil->tanggal_transaksi = $penyaluranBiaya->approved_at;
                        $newNonBagiHasil->keterangan = 'Pencairan Penyaluran Manfaat';
                        $newNonBagiHasil->saldo = $penyaluranBiaya->nominal_peminjaman;
                        $newNonBagiHasil->type = 'kredit';
                        $newNonBagiHasil->penyaluran_id = $penyaluranBiaya->id;
                        $newNonBagiHasil = $newNonBagiHasil->save();
        
                        if (!$newNonBagiHasil) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
        
                        break;
        
                    default:
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            //pjp=piutang jangka pendek, pja=piutang jangka panjang
            switch ($jenisPiutang) {
                case "pjp":
                    $newPjp = new PiutangJangkaPendek();
                    $newPjp->tanggal_transaksi = $penyaluranBiaya->approved_at;
                    $newPjp->keterangan = 'Pencairan Penyaluran Manfaat';
                    $newPjp->saldo = $penyaluranBiaya->nominal_peminjaman;
                    $newPjp->type = 'debit';
                    $newPjp->penyaluran_id = $penyaluranBiaya->id;
                    $newPjp = $newPjp->save();

                    if (!$newPjp) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;
                case "pja":
                    $newPja = new PiutangJangkaPanjang();
                    $newPja->tanggal_transaksi = $penyaluranBiaya->approved_at;
                    $newPja->keterangan = 'Pencairan Penyaluran Manfaat';
                    $newPja->saldo = $penyaluranBiaya->nominal_peminjaman;
                    $newPja->type = 'kredit';
                    $newPja->penyaluran_id = $penyaluranBiaya->id;
                    $newPja = $newPja->save();

                    if (!$newPja) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPenyaluranBiaya, "Success", false);

        }catch (Exception $e) {
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

            $datas = Penyaluran::with("PiutangJangkaPendek","PiutangJangkaPanjang")->paginate($request->limit);
            foreach ($datas as $d_key => $data) {
                $data["nominal"] = empty($data["PiutangJangkaPendek"]) ? $data->PiutangJangkaPanjang['saldo'] : $data->PiutangJangkaPendek['saldo'];
                $data["tanggal_transaksi"] = empty($data["PiutangJangkaPendek"]) ? $data->PiutangJangkaPanjang['tanggal_transaksi'] : $data->PiutangJangkaPendek['tanggal_transaksi'];
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
            $penyaluranBiaya = Penyaluran::select('nama_penerima','nik','alamat','no_telepon','jenis_usaha','deskripsi_usaha','sumber_biaya','jenis_piutang','nominal_peminjaman','periode_peminjaman','periode_awal','periode_akhir')
            ->where('id',$id)->get();
            
            return Response::HttpResponse(200, $penyaluranBiaya, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Update(Request $request, $id)
    {
        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_penerima' => 'required|string|max:255',
                'nik' => 'required|numeric|max:255',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|numeric|max:255',
                'jenis_usaha' => 'required|in:perdagangan,fashion,otomotif,kerajinan,it,lainnya',
                'deskripsi_usaha' => 'required|string|max:255',
                'nominal_peminjaman' => 'required|numeric',
                'sumber_biaya' => 'required|in:bagihasil,nonbagihasil',
                //pjp=piutang jangka pendek, pja=piutang jangka panjang
                'jenis_piutang' => 'required|in:pjp,pja',
                'periode_peminjaman' => 'required|numeric',
                'periode_awal' => 'required|date_format:Y-m-d',
                'periode_akhir' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", true);
            }

            DB::beginTransaction();

            $penyaluranBiaya = Penyaluran::find($id);
            //dd($penyaluranBiaya, $id);
            
            $penyaluranTemp->nama_penerima = $request->nama_penerima;
            $penyaluranTemp->nik = $request->nik;
            $penyaluranTemp->alamat = $request->alamat;
            $penyaluranTemp->telepon = $request->telepon;
            $penyaluranTemp->jenis_usaha = $request->jenis_usaha;
            $penyaluranTemp->deskripsi_usaha = $request->deskripsi_usaha;
            $penyaluranTemp->nominal_peminjaman = $request->nominal_peminjaman;
            $penyaluranTemp->sumber_biaya = $request->sumber_biaya;
            $penyaluranTemp->jenis_piutang = $request->jenis_piutang;
            $penyaluranTemp->periode_peminjaman = $request->periode_peminjaman;
            $penyaluranTemp->periode_awal = $request->periode_awal;
            $penyaluranTemp->periode_akhir = $request->periode_akhir;
            $penyaluranTemp->created_by = $this->admin->name;
            $penyaluranTemp->modified_by = $this->admin->name;

            $newPenyaluran = $penyaluranBiaya->save();

            if (!$newPenyaluran) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPenyaluran, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Delete(Request $request,int $id) {

        try {
            $currData = Penyaluran::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->name;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function DropdownSumberBiaya(Request $request){
        $sumber=['bagihasil'=>'Kas Bagi Hasil','nonbagihasil'=>'Kas Non Bagi Hasil'];
        
        return Response::HttpResponse(200, $sumber, "Success", true);
    }

    public function DropdownJenisPiutang(Request $request){
        $jenis_piutang = ['pjp'=>'Piutang Jangka Pendek','pja'=>'Piutang Jangka Panjang'];

        return Response::HttpResponse(200, $jenis_piutang, "Success", true);
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

        $datas = Penyaluran::where($inputs["search_type"],"like","%".$inputs["value"]."%")->paginate(10);
        return Response::HttpResponse(200, $datas, "OK", false);
    }

    public function TesKelayakan(Request $request, $id)
    {
        try 
        {
            $skor = TempTable::join("answers","temp_table.answer_id","=","answers.id")
            ->select(DB::raw(SUM("answers.score")))   
            ->where('temp_table.penyaluran_id',$id)             
            ->get();

            /* $answerCount = TempTable::join("answers","temp_table.answer_id","=","answers.id")
            ->count(DB::raw("answers.score");

            for ($i=1; $i<=TempTable::count('id'); $i++) {
                $newTempTable = TempTable::where('id',$i)->update([
                        'saldo' => $saldo_terakhir_kas[$i-1],
         
                ]);
                } */


                $skor_akhir=($skor/$skor_max)*100;

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
            return Response::HttpResponse(200, $skor_akhir, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }


            
}