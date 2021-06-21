<?php

namespace App\Http\Controllers\Wakaf;

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

use Mockery\Exception;


Class PelunasanPiutang
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
                //tgl cicilan ga diinput jg?
                //gmn kalo suruh input nik dulu? baru hbs itu ambil data based on nik
                'tanggal_cicilan' => 'required|date_format:Y-m-d',
                'nik' => 'required|string|max:255',
                //'nama_peminjam' => 'required|string|max:255',
                'jumlah_cicilan' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }
            
            
            DB::beginTransaction();
            //find id dengan nik yang sama, ambil data jumlah piutang dari tabel piutang dan periode akhir
            $pelunasan = new Pelunasan();
            //$pelunasanPenyaluran = Penyaluran::where('nik',$request->nik)->where('pelunasan','0');
            $namaPeminjam = Penyaluran::where('nik',$request->nik)->first('nama_penerima');
            $jumlahPinjaman = Penyaluran::where('nik',$request->nik)->sum('nominal_peminjaman');
            $periodeAkhir = Penyaluran::where('nik',$request->nik)->first('periode_akhir');
            //$pelunasanPenyaluran = Penyaluran::where('nik',$request->nik)->where('pelunasan','0');
            $nikPelunasanQuery = Pelunasan::where('nik',$request->nik)->first('nik');
            if($nikPelunasanQuery == null)
            {
                $nikQuery = Penyaluran::where('nik',$request->nik)->first('nik');
                if($nikQuery == null)
                {
                    return Response::HttpResponse(400, null, "NIK not found", true);
                }
                $kekuranganCicilan = $jumlahPinjaman - $request->jumlah_cicilan;
            }else
            {
                $kekuranganCicilan =  (Pelunasan::where('nik',$request->nik)->sum('kekurangan')) - $request->jumlah_cicilan;
            }

            
            
            $pelunasan->tanggal_cicilan = $request->tanggal_cicilan;
            $pelunasan->nik = $request->nik;
            $pelunasan->nama_peminjam = $namaPeminjam->nama_penerima;
            $pelunasan->jumlah_cicilan = $request->jumlah_cicilan;
            $pelunasan->kekurangan = $kekuranganCicilan;
            
            $pelunasan->tanggal_jatuh_tempo = $periodeAkhir->periode_akhir;
            if($pelunasan->kekurangan == 0)
            {
                $pelunasan->pelunasan = 1;

            }
            $pelunasan->created_by = $this->admin->name;
            $pelunasan->modified_by = $this->admin->name;
            
            
            $newPelunasan = $pelunasan->save();

            if (!$newPelunasan) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data", true);
            }

            
            DB::commit();

            return Response::HttpResponse(200, $newPelunasan, "Success", false);
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

            //$datas = Pelunasan::all()->paginate($request->limit);
            $datas = Pelunasan::paginate($request->limit);
            foreach ($datas as $d_key => $data) {
                //$data["pelunasan"] = null;
                
                if ($data["pelunasan"] == 0 || null){
                    $data["pelunasan"] = (string) 'Belum Lunas';

                }else{
                    $data["pelunasan"] = (string) 'Lunas';
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
            $pelunasan = Pelunasan::select('tanggal_cicilan','nama_peminjam','nik','jumlah_cicilan','kekurangan','tanggal_jatuh_tempo','status_pelunasan')
            ->where('id',$id)->get();
            return Response::HttpResponse(200, $pelunasan, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Update(Request $request, $id)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                //tgl cicilan ga diinput jg?
                //gmn kalo suruh input nik dulu? baru hbs itu ambil data based on nik
                'tanggal_cicilan' => 'required|date_format:Y-m-d',
                'nik' => 'required|string|max:255',
                //'nama_peminjam' => 'required|string|max:255',
                'jumlah_cicilan' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();
            //find id dengan nik yang sama, ambil data jumlah piutang dari tabel piutang dan periode akhir
            $pelunasan = Pelunasan::find($id);
        
            $namaPeminjam = Penyaluran::where('nik',$request->nik)->first('nama_penerima');
            $jumlahPinjaman = Penyaluran::where('nik',$request->nik)->sum('nominal_peminjaman');
            $periodeAkhir = Penyaluran::where('nik',$request->nik)->first('periode_akhir');

            $idQuery = Pelunasan::where('nik',$request->nik)->where('id','<>',$id)->count('id');
            if($idQuery == 0)
            {
                $kekuranganCicilan = $jumlahPinjaman - $request->jumlah_cicilan;
                $pelunasan->kekurangan = $kekuranganCicilan;
            }else
            {
                $sumKekurangan = Pelunasan::where('nik',$request->nik)->where('id','<>',$id)->sum('kekurangan');
                $kekuranganCicilan =  $sumKekurangan - $request->jumlah_cicilan;
                
                $pelunasan->kekurangan = $kekuranganCicilan;
            }

            $pelunasan->tanggal_cicilan = $request->tanggal_cicilan;
            $pelunasan->nik = $request->nik;
            $pelunasan->nama_peminjam = $namaPeminjam->nama_penerima;
            $pelunasan->jumlah_cicilan = $request->jumlah_cicilan;
            
            $pelunasan->tanggal_jatuh_tempo = $periodeAkhir->periode_akhir;
            if($pelunasan->kekurangan == 0)
            {
                $pelunasan->pelunasan = '1';

            }
            $pelunasan->created_by = $this->admin->name;
            $pelunasan->modified_by = $this->admin->name;

            $newPelunasan = $pelunasan->save();

            if (!$newPelunasan) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data", true);
            }

            
            DB::commit();

            return Response::HttpResponse(200, $newPelunasan, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Delete(Request $request,int $id) {
        try {
            $currData = Pelunasan::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->name;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
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

        $datas = Pelunasan::where($inputs["search_type"],"like","%".$inputs["value"]."%")->paginate(10);
        return Response::HttpResponse(200, $datas, "OK", false);
    }
}