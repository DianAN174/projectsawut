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
            
            $pelunasanPiutang = new Pelunasan();
            $namaPeminjam = Penyaluran::where('nik',$request->nik)->first('nama_penerima');
            $periodeAkhir = Penyaluran::where('nik',$request->nik)->first('periode_akhir');
            //cari data dengan status pelunasan 1
            $statusLunas =  Pelunasan::where('nik',$request->nik)->where('pelunasan',1)->first('id');
            //cek apakah sudah ada entry dgn nik yg sama sebelumnya
            $nikPelunasanQuery = Pelunasan::where('nik',$request->nik)->first('nik');
            
            if($nikPelunasanQuery == null)
            {
                $nikQuery = Penyaluran::where('nik',$request->nik)->first('nik');
                if($nikQuery == null)
                {
                    return Response::HttpResponse(400, null, "NIK not found", true);
                }
  
                $penyaluran = Penyaluran::where('nik',$request->nik)->first('id');
                $penyaluran->pelunasan = 0;
                $penyaluran = $penyaluran->save();

                if($statusLunas !== null)
                {
                    $pelunasanPiutang->kekurangan = 0;
                    
                }
                else{
                $jumlahPinjaman = Penyaluran::where('nik',$request->nik)->where('pelunasan',0)->sum('nominal_peminjaman');
            
                $kekuranganCicilan = $jumlahPinjaman - $request->jumlah_cicilan;
                $pelunasanPiutang->kekurangan = $kekuranganCicilan;
                }
            }
            else
            {
                if($statusLunas !== null)
                {
                    $pelunasanPiutang->kekurangan = 0;
                }
                else{
                $jumlahPinjaman = Penyaluran::where('nik',$request->nik)->where('pelunasan',0)->sum('nominal_peminjaman');
            
                $kekuranganCicilan =  $jumlahPinjaman - (Pelunasan::where('nik',$request->nik)->sum('jumlah_cicilan') + $request->jumlah_cicilan);
                $pelunasanPiutang->kekurangan = $kekuranganCicilan;
                }
            }

            
            $pelunasanPiutang->jumlah_cicilan = $request->jumlah_cicilan;
            $pelunasanPiutang->tanggal_cicilan = $request->tanggal_cicilan;
            $pelunasanPiutang->nik = $request->nik;
            $pelunasanPiutang->nama_peminjam = $namaPeminjam->nama_penerima;
            $pelunasanPiutang->tanggal_jatuh_tempo = $periodeAkhir->periode_akhir;
            
            if($pelunasanPiutang->kekurangan == 0)
            {
                $pelunasanPiutang->pelunasan = 1;
                //jika sudah lunas, isi flag pelunasan di tabel penyaluran dengan 1
                $penyaluran = Penyaluran::where('nik',$request->nik)->first('id');
                $penyaluran->pelunasan = 1;
                $penyaluran = $penyaluran->save();
            }
            elseif($pelunasanPiutang->kekurangan > 0){
                $pelunasanPiutang->pelunasan = 0;
                $penyaluran = Penyaluran::where('nik',$request->nik)->first('id');
                $penyaluran->pelunasan = 0;
                $penyaluran = $penyaluran->save();
            }
            elseif($pelunasanPiutang->kekurangan < 0)
            {
                $pelunasanPiutang->kekurangan = 0;
                $pelunasanPiutang->pelunasan = 1;
                $penyaluran = Penyaluran::where('nik',$request->nik)->first('id');
                $penyaluran->pelunasan = 1;
                $penyaluran = $penyaluran->save();
            }
            
            $pelunasanPiutang->created_by = $this->admin->nama_pengguna;
            $pelunasanPiutang->modified_by = $this->admin->nama_pengguna;
            
            
            $newPelunasan = $pelunasanPiutang->save();

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
            $datas = Pelunasan::select('tanggal_cicilan','nama_peminjam','nik','jumlah_cicilan','kekurangan','tanggal_jatuh_tempo')
            ->where('id',$id)->get();
            
            return Response::HttpResponse(200, $datas, "Info User yang akan diedit berhasil ditampilkan", false);
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
            $pelunasanPiutang = Pelunasan::find($id);
        
            $namaPeminjam = Penyaluran::where('nik',$request->nik)->first('nama_penerima');
            $jumlahPinjaman = Penyaluran::where('nik',$request->nik)->where('pelunasan',0)->sum('nominal_peminjaman');
            $periodeAkhir = Penyaluran::where('nik',$request->nik)->first('periode_akhir');
            //cari data dengan status pelunasan 1
            $statusLunas =  Pelunasan::where('nik',$request->nik)->where('pelunasan',1)->first('id');

            $getIdPelunasanLess = Pelunasan::where('nik',$request->nik)->where('id','<',$id)->pluck('id')->toArray();
            $getIdPelunasanGreater = Pelunasan::where('nik',$request->nik)->where('id','>',$id)->pluck('id')->toArray();
            
            //cek apakah sudah ada entry dgn nik yg sama sebelumnya
            $nikPelunasanQuery = Pelunasan::where('nik',$request->nik)->first('nik');
            if($nikPelunasanQuery->nik == $request->nik)
            {
                $kekuranganCicilan =  $jumlahPinjaman - (Pelunasan::where('id','<',$id)->where('nik',$request->nik)->sum('jumlah_cicilan') + $request->jumlah_cicilan);
                
                $pelunasanPiutang->kekurangan = $kekuranganCicilan;
                
                $pelunasanPiutang->jumlah_cicilan = $request->jumlah_cicilan;

                if(!empty($getIdPelunasanGreater))
                {
                    for ($i=0; $i<sizeof($getIdPelunasanGreater); $i++) {
                    $newPelunasan = Pelunasan::where('id',$getIdPelunasanGreater[$i])->first('id');
                    $jumlahCicilanNew = Pelunasan::where('id',$getIdPelunasanGreater[$i])->first('jumlah_cicilan');
                    $newPelunasan->kekurangan = $jumlahPinjaman - (Pelunasan::where('id','<',$id)->where('nik',$request->nik)->sum('jumlah_cicilan') + $request->jumlah_cicilan + $jumlahCicilanNew->jumlah_cicilan);
                    $newPelunasan = $newPelunasan->save();
                    }
                }
                
            }else
            {
                $nikQuery = Penyaluran::where('nik',$request->nik)->first('nik');
                if($nikQuery == null)
                {
                    return Response::HttpResponse(400, null, "NIK not found", true);
                }
                
            $pelunasanPiutang->jumlah_cicilan = $request->jumlah_cicilan;

                $kekuranganCicilan = $jumlahPinjaman - $request->jumlah_cicilan;
                $pelunasanPiutang->kekurangan = $kekuranganCicilan;
               
            }

            $pelunasanPiutang->tanggal_cicilan = $request->tanggal_cicilan;
            $pelunasanPiutang->nik = $request->nik;
            $pelunasanPiutang->nama_peminjam = $namaPeminjam->nama_penerima;
            
            $pelunasanPiutang->tanggal_jatuh_tempo = $periodeAkhir->periode_akhir;
            
            if($pelunasanPiutang->kekurangan == 0)
            {
                $pelunasanPiutang->pelunasan = 1;
                //jika sudah lunas, isi flag pelunasan di tabel penyaluran dengan 1
                $penyaluran = Penyaluran::where('nik',$request->nik)->first('id');
                $penyaluran->pelunasan = 1;
                $penyaluran = $penyaluran->save();
            }
            elseif($pelunasanPiutang->kekurangan > 0){
                $pelunasanPiutang->pelunasan = 0;
                $penyaluran = Penyaluran::where('nik',$request->nik)->first('id');
                $penyaluran->pelunasan = 0;
                $penyaluran = $penyaluran->save();
            }
            elseif($pelunasanPiutang->kekurangan < 0)
            {
                $pelunasanPiutang->kekurangan = 0;
                $pelunasanPiutang->pelunasan = 1;
                $penyaluran = Penyaluran::where('nik',$request->nik)->first('id');
                $penyaluran->pelunasan = 1;
                $penyaluran = $penyaluran->save();
            }

            $pelunasanPiutang->created_by = $this->admin->nama_pengguna;
            $pelunasanPiutang->modified_by = $this->admin->nama_pengguna;

            $newPelunasan = $pelunasanPiutang->save();

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

            $currData->deleted_by = $this->admin->nama_pengguna;

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