<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\ModelPengelolaan\KasTunai;
use App\Models\ModelPengelolaan\KasTabBagiHasil;
use App\Models\ModelPengelolaan\KasTabNonbagiHasil;
use App\Models\ModelPengajuanBiaya\PengajuanBiaya;
use App\Models\ModelPengajuanBiaya\BebanPengelolaandanPengembangan;
use App\Models\ModelPengajuanBiaya\BagianNazhir;
use App\Models\ModelPengajuanBiaya\PentasyarufanManfaat;
use App\Models\User;

use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class PengajuanBiayaOperasional
{
    protected $admin;

    public function __construct(User $user)
    {
        $this->admin = $user;
    }

    public function Create(Request $request)
    {
        try
        {
            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_pengaju' => 'required|string|max:255',
                //'kategori_biaya' => 'required|in:bebanpengelolaan,bagiannazhir,pentasyarufan',
                'kategori_biaya' => 'required',
                //'jenis_biaya' => 'required',
                'keterangan_biaya' => 'required|string|max:255',
                'nominal' => 'required|numeric',
                'sumber_biaya' => 'required|in:tunai,bagihasil,nonbagihasil',
                'tanggal_transaksi' => 'nullable|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            if($request->tanggal_transaksi==null){
                $request->tanggal_transaksi = \Carbon\Carbon::now();
            }

            DB::beginTransaction();

            $pengajuanBiaya = new PengajuanBiaya();

            $pengajuanBiaya->nama_pengaju = $request->nama_pengaju;
            $pengajuanBiaya->kategori_biaya = $request->kategori_biaya;
            //$pengajuanBiaya->jenis_biaya = $request->jenis_biaya;
            $pengajuanBiaya->keterangan_biaya = $request->keterangan_biaya;
            $pengajuanBiaya->nominal = $request->nominal;
            $pengajuanBiaya->sumber_biaya = $request->sumber_biaya;

            $pengajuanBiaya->created_by = $this->admin->nama_pengguna;
            $pengajuanBiaya->modified_by = $this->admin->nama_pengguna;

            $newPengajuanBiaya = $pengajuanBiaya->save();

            if (!$newPengajuanBiaya) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPengajuanBiaya, "Success", false);

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

            $datas = PengajuanBiaya::with("KasTunai","KasTabBagiHasil","KasTabNonBagiHasil")->paginate($request->limit);
            foreach ($datas as $d_key => $data) {
                if ($data["kategori_biaya"] == 'atk'){
                    $data["kategori_biaya"] = (string) 'Beban ATK';
                }elseif ($data["kategori_biaya"] == 'rapat'){
                    $data["kategori_biaya"] = (string) 'Beban Rapat';
                }elseif ($data["kategori_biaya"] == 'penyaluran'){
                    $data["kategori_biaya"] = (string) 'Beban Penyaluran Manfaat Wakaf';
                }elseif ($data["kategori_biaya"] == 'administrasi'){
                    $data["kategori_biaya"] = (string) 'Beban Administrasi Bank';
                }elseif ($data["kategori_biaya"] == 'pajak'){
                    $data["kategori_biaya"] = (string) 'Beban Pajak';

                }elseif ($data["kategori_biaya"] == 'insentif'){
                    $data["kategori_biaya"] = (string) 'Insentif Nazhir';
                }elseif ($data["kategori_biaya"] == 'tunjanganKesehatan'){
                    $data["kategori_biaya"] = (string) 'Tunjangan Kesehatan';

                }elseif ($data["kategori_biaya"] == 'ekonomiUmat'){
                    $data["kategori_biaya"] = (string) 'Ekonomi Umat';
                }elseif ($data["kategori_biaya"] == 'kesejahteraan'){
                    $data["kategori_biaya"] = (string) 'Kesejahteraan Umat';
                }elseif ($data["kategori_biaya"] == 'ibadah'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Ibadah';
                }elseif ($data["kategori_biaya"] == 'pendidikan'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Pendidikan';
                }elseif ($data["kategori_biaya"] == 'kesehatan'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Kesehatan';
                }elseif ($data["kategori_biaya"] == 'bantuan'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Bantuan';
                }

            

            /* foreach ($datas as $d_key => $data) {

                //$data["tanggal_transaksi"] = null;
                $data["nominal"] = null;

                if (empty($data["KasTunai"])){
                    switch (true) {
                        case empty($data["KasTabBagiHasil"]):
                            //$data["tanggal_transaksi"] = $data->KasTabNonBagiHasil['tanggal_transaksi'];
                            $data["nominal"] = $data->KasTabNonBagiHasil['saldo'];
                            break;

                        case empty($data["KasTabNonBagiHasil"]):
                            //$data["tanggal_transaksi"] = $data->KasTabBagiHasil['tanggal_transaksi'];
                            $data["nominal"] = $data->KasTabBagiHasil['saldo'];
                        break;
                
                        default:
                            
                        break;
                    }
                }else{
                    //$data["tanggal_transaksi"] = $data->KasTunai['tanggal_transaksi'];
                    $data["nominal"] = $data->KasTunai['saldo'];
                }
            } */
        
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
            $datas = PengajuanBiaya::select('nama_pengaju','kategori_biaya','keterangan_biaya','nominal','sumber_biaya')
            ->where('id',$id)->get();
            //$datas = PengajuanBiaya::find($id);
            /* foreach ($datas as $d_key => $data) {
                if ($data["kategori_biaya"] == 'atk'){
                    $data["kategori_biaya"] = (string) 'Beban ATK';
                }elseif ($data["kategori_biaya"] == 'rapat'){
                    $data["kategori_biaya"] = (string) 'Beban Rapat';
                }elseif ($data["kategori_biaya"] == 'penyaluran'){
                    $data["kategori_biaya"] = (string) 'Beban Penyaluran Manfaat Wakaf';
                }elseif ($data["kategori_biaya"] == 'administrasi'){
                    $data["kategori_biaya"] = (string) 'Beban Administrasi Bank';
                }elseif ($data["kategori_biaya"] == 'pajak'){
                    $data["kategori_biaya"] = (string) 'Beban Pajak';

                }elseif ($data["kategori_biaya"] == 'insentif'){
                    $data["kategori_biaya"] = (string) 'Insentif Nazhir';
                }elseif ($data["kategori_biaya"] == 'tunjanganKesehatan'){
                    $data["kategori_biaya"] = (string) 'Tunjangan Kesehatan';

                }elseif ($data["kategori_biaya"] == 'ekonomiUmat'){
                    $data["kategori_biaya"] = (string) 'Ekonomi Umat';
                }elseif ($data["kategori_biaya"] == 'kesejahteraan'){
                    $data["kategori_biaya"] = (string) 'Kesejahteraan Umat';
                }elseif ($data["kategori_biaya"] == 'ibadah'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Ibadah';
                }elseif ($data["kategori_biaya"] == 'pendidikan'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Pendidikan';
                }elseif ($data["kategori_biaya"] == 'kesehatan'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Kesehatan';
                }elseif ($data["kategori_biaya"] == 'bantuan'){
                    $data["kategori_biaya"] = (string) 'Kegiatan Bantuan';
                }
            } */
            return Response::HttpResponse(200, $datas, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Update(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_pengaju' => 'required|string|max:255',
                //'kategori_biaya' => 'required|in:bebanpengelolaan,bagiannazhir,pentasyarufan',
                'kategori_biaya' => 'required|string',
                //'jenis_biaya' => 'required',
                'keterangan_biaya' => 'required|string|max:255',
                'nominal' => 'required|numeric',
                'sumber_biaya' => 'required|in:tunai,bagihasil,nonbagihasil',
                'tanggal_transaksi' => 'nullable|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            if($request->tanggal_transaksi==null){
                $request->tanggal_transaksi = \Carbon\Carbon::now();
            }

            DB::beginTransaction();

            $pengajuanBiaya = PengajuanBiaya::find($id);

            if($pengajuanBiaya->approval == 1)
            {
                $kategoriBiaya = $pengajuanBiaya->kategori_biaya;
                //case untuk kategori_biaya yg sudah diinput
                if($kategoriBiaya == 'atk' || $kategoriBiaya == 'pemasaran' || $kategoriBiaya == 'rapat' || $kategoriBiaya == 'penyaluran' || $kategoriBiaya == 'administrasi' || $kategoriBiaya == 'pajak')
                {
                    $kategoriBiayaCase = 1;
                }
                elseif($kategoriBiaya == 'insentif' || $kategoriBiaya == 'tunjanganKesehatan'){
                    $kategoriBiayaCase = 2;
                }
                elseif($kategoriBiaya == 'ekonomiUmat' || $kategoriBiaya =='kesejahteraan' || $kategoriBiaya =='ibadah' || $kategoriBiaya =='pendidikan' || $kategoriBiaya =='kesehatan' || $kategoriBiaya =='bantuan'){
                    $kategoriBiayaCase = 3;
                }
                
                $newKategori = $request->kategori_biaya;
                //case untuk nilai kategori_biaya yang baru
                if($newKategori == 'atk' || $newKategori =='pemasaran' || $newKategori =='rapat' || $newKategori =='penyaluran' || $newKategori =='administrasi' || $newKategori =='pajak')
                {
                    $kategoriCase = 1;
                }
                elseif($newKategori == 'insentif' || $newKategori =='tunjanganKesehatan'){
                    $kategoriCase = 2;
                }
                elseif($newKategori == 'ekonomiUmat' || $newKategori =='kesejahteraan' || $newKategori =='ibadah' || $newKategori =='pendidikan' || $newKategori =='kesehatan' || $newKategori =='bantuan'){
                    $kategoriCase = 3;
                }

                $sumberBiaya = $pengajuanBiaya->sumber_biaya;
            
                switch ($request->sumber_biaya) {
                    case "tunai":
                    if($sumberBiaya == $request->sumber_biaya)
                    {
                        $newKasTunai = KasTunai::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                        $newKasTunai->modified_by = $this->admin->nama_pengguna;
                    }
                    else{
                        $newKasTunai = new KasTunai();
                        $newKasTunai->created_by = $this->admin->nama_pengguna;
                    }
                    $newKasTunai = KasTunai::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                    $newKasTunai->tanggal_transaksi = $request->tanggal_transaksi;
                    $newKasTunai->keterangan = $request->keterangan;
                    $newKasTunai->saldo = $request->nominal;
                    $newKasTunai->type = 'pengeluaran';
                    $newKasTunai->pengajuan_biaya_id = $pengajuanBiaya->id;
                    $newKasTunai = $newKasTunai->save();
        
                        if (!$newKasTunai) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }

                    break;

                    case "bagihasil":
                        if($sumberBiaya == $request->sumber_biaya)
                        {
                            $newBagiHasil = KasTabBagiHasil::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newBagiHasil->modified_by = $this->admin->nama_pengguna;
                        }
                        else{
                            $newBagiHasil = new KasTabBagiHasil();
                            $newBagiHasil->created_by = $this->admin->nama_pengguna;
                        }
                            $newBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                            $newBagiHasil->keterangan = $request->keterangan;
                            $newBagiHasil->saldo = $request->nominal;
                            $newBagiHasil->type = 'pengeluaran';
                            $newBagiHasil->pengajuan_biaya_id = $pengajuanBiaya->id;
                            $newBagiHasil = $newBagiHasil->save();
            
                            if (!$newBagiHasil) {
                                DB::rollBack();
                                return Response::HttpResponse(400, null, "Failed to create data ", true);
                            }
            
                            break;
                        case "nonbagihasil":
                            if($sumberBiaya == $request->sumber_biaya)
                            {
                                $newNonBagiHasil = KasTabNonBagiHasil::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                                $newNonBagiHasil->modified_by = $this->admin->nama_pengguna;
                            }
                            else{
                                $newNonBagiHasil = new KasTabNonBagiHasil();
                                $newNonBagiHasil->created_by = $this->admin->nama_pengguna;
                            }
                            $newNonBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                            $newNonBagiHasil->keterangan = $request->keterangan;
                            $newNonBagiHasil->saldo = $request->nominal;
                            $newNonBagiHasil->type = 'pengeluaran';
                            $newNonBagiHasil->pengajuan_biaya_id = $pengajuanBiaya->id;
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
                
                switch ($kategoriCase) {
                    case 1:
                        if($kategoriBiayaCase == $kategoriCase)
                        {
                            $newBPP = BebanPengelolaandanPengembangan::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newBPP->modified_by = $this->admin->nama_pengguna;
                        }
                        else{
                            $newBPP = new BebanPengelolaandanPengembangan();
                            $newBPP->created_by = $this->admin->nama_pengguna;
                        }
                        $newBPP->tanggal_transaksi = $request->tanggal_transaksi;
                        $newBPP->keterangan = $request->keterangan;
                        $newBPP->saldo = $request->nominal;
                        $newBPP->type = 'pemasukan';
                        $newBPP->pengajuan_biaya_id = $pengajuanBiaya->id;
                        $newBPP = $newBPP->save();

                        if (!$newBPP) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }

                        break;
                    case 2:
                        if($kategoriBiayaCase == $kategoriCase)
                        {
                            $newBagianNazhir = BagianNazhir::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newBagianNazhir->modified_by = $this->admin->nama_pengguna;
                        }
                        else{
                            $newBagianNazhir = new BagianNazhir();
                            $newBagianNazhir->created_by = $this->admin->nama_pengguna;
                        }
                        $newBagianNazhir->tanggal_transaksi = $request->tanggal_transaksi;
                        $newBagianNazhir->keterangan = $request->keterangan;
                        $newBagianNazhir->saldo = $request->nominal;
                        $newBagianNazhir->type = 'pemasukan';
                        $newBagianNazhir->pengajuan_biaya_id = $pengajuanBiaya->id;
                        $newBagianNazhir = $newBagianNazhir->save();

                        if (!$newBagianNazhir) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }

                    break;

                    case 3:
                        if($kategoriBiayaCase == $kategoriCase)
                        {
                            $newPentasyarufan = PentasyarufanManfaat::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newPentasyarufan->modified_by = $this->admin->nama_pengguna;
                        }
                        else{
                            $newPentasyarufan = new PentasyarufanManfaat();
                            $newPentasyarufan->created_by = $this->admin->nama_pengguna;
                        }
                        $newPentasyarufan->tanggal_transaksi = $request->tanggal_transaksi;
                        $newPentasyarufan->keterangan = $request->keterangan;
                        $newPentasyarufan->saldo = $request->nominal;                        
                        $newPentasyarufan->type = 'pemasukan';
                        $newPentasyarufan->pengajuan_biaya_id = $pengajuanBiaya->id;
                        $newPentasyarufan = $newPentasyarufan->save();

                        if (!$newPentasyarufan) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
                        
                    break;

                    default:
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

                //delete transaksi yg diedit / pindah akun
                if($sumberBiaya !== $request->sumber_biaya)
                {
                    switch ($sumberBiaya) {
                        case "tunai":
                        $newKasTunai = KasTunai::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                        $newKasTunai->deleted_at = \Carbon\Carbon::now();
                        $newKasTunai->deleted_by = $this->admin->nama_pengguna;
                        $newKasTunai = $newKasTunai->save();
                        
                        if (!$newKasTunai) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                        }
                        break;

                        case "bagihasil":
                            $newBagiHasil = KasTabBagiHasil::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newBagiHasil->deleted_at = \Carbon\Carbon::now();
                            $newBagiHasil->deleted_by = $this->admin->nama_pengguna;
                            $newBagiHasil = $newBagiHasil->save();
                            
                            if (!$newBagiHasil) {
                                DB::rollBack();
                                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                            }
                            break;
                        
                        case "nonbagihasil":
                            $newNonBagiHasil = KasTabNonBagiHasil::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newNonBagiHasil->deleted_at = \Carbon\Carbon::now();
                            $newNonBagiHasil->deleted_by = $this->admin->nama_pengguna;
                            $newNonBagiHasil = $newNonBagiHasil->save();
                            
                            if (!$newNonBagiHasil) {
                                DB::rollBack();
                                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                            }
                            break;
                    }
                }

                if($kategoriBiayaCase !== $kategoriCase)
                {
                        switch ($kategoriBiayaCase) {
                        case 1:
                        $newBPP = BebanPengelolaandanPengembangan::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                        $newBPP->deleted_at = \Carbon\Carbon::now();
                        $newBPP->deleted_by = $this->admin->nama_pengguna;
                        $newBPP = $newBPP->save();
                        
                        if (!$newBPP) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                        }
                        break;

                        case 2:
                            $newBagianNazhir = BagianNazhir::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newBagianNazhir->deleted_at = \Carbon\Carbon::now();
                            $newBagianNazhir->deleted_by = $this->admin->nama_pengguna;
                            $newBagianNazhir = $newBagianNazhir->save();
                            
                            if (!$newBagianNazhir) {
                                DB::rollBack();
                                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                            }
                            break;

                        case 3:
                            $newPentasyarufan = PentasyarufanManfaat::where('pengajuan_biaya_id',$pengajuanBiaya->id)->first('id');
                            $newPentasyarufan->deleted_at = \Carbon\Carbon::now();
                            $newPentasyarufan->deleted_by = $this->admin->nama_pengguna;
                            $newPentasyarufan = $newPentasyarufan->save();
                            
                            if (!$newPentasyarufan) {
                                DB::rollBack();
                                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                            }
                            break;
                    }
                }
        }

            $pengajuanBiaya->nama_pengaju = $request->nama_pengaju;
            $pengajuanBiaya->kategori_biaya = $request->kategori_biaya;
            $pengajuanBiaya->keterangan_biaya = $request->keterangan_biaya;
            $pengajuanBiaya->nominal = $request->nominal;
            $pengajuanBiaya->sumber_biaya = $request->sumber_biaya;
            $pengajuanBiaya->created_by = $this->admin->nama_pengguna;
            $pengajuanBiaya->modified_by = $this->admin->nama_pengguna;
    
            $newPengajuanBiaya = $pengajuanBiaya->save();
    
            if (!$newPengajuanBiaya) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPengajuanBiaya, "Success", false);

        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Delete(Request $request,int $id) {
        try {
            $currData = PengajuanBiaya::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->nama_pengguna;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Persetujuan(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();
            $pengajuanBiaya = PengajuanBiaya::find($id);

            $approval = $pengajuanBiaya->approval;
            
            DB::beginTransaction();

            if($approval==0)
            {
                $pengajuanBiaya->approval = 1;
                //$pengajuanBiaya->status_persetujuan = 'approved';
                
                $pengajuanBiaya->approved_at = \Carbon\Carbon::now();
                $pengajuanBiaya->approved_by = $this->admin->nama_pengguna;
                
            }

            $newPengajuanBiaya = $pengajuanBiaya->save();

                if (!$newPengajuanBiaya) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            $newKategori = $request->kategori_biaya;
                //case untuk nilai kategori_biaya yang baru
                if($newKategori == 'atk' || $newKategori =='pemasaran' || $newKategori =='rapat' || $newKategori =='penyaluran' || $newKategori =='administrasi' || $newKategori =='pajak')
                {
                    $kategoriCase = 1;
                }
                elseif($newKategori == 'insentif' || $newKategori =='tunjanganKesehatan'){
                    $kategoriCase = 2;
                }
                elseif($newKategori == 'ekonomiUmat' || $newKategori =='kesejahteraan' || $newKategori =='ibadah' || $newKategori =='pendidikan' || $newKategori =='kesehatan' || $newKategori =='bantuan'){
                    $kategoriCase = 3;
                }

            $sumberBiaya = $pengajuanBiaya->sumber_biaya;
        
            switch ($sumberBiaya) {
                case "tunai":
                $newKasTunai = new KasTunai();
                $newKasTunai->tanggal_transaksi = $request->tanggal_transaksi;
                $newKasTunai->keterangan = $request->keterangan;
                $newKasTunai->saldo = $pengajuanBiaya->nominal;
                $newKasTunai->type = 'pengeluaran';
                $newKasTunai->pengajuan_biaya_id = $pengajuanBiaya->id;
                $newKasTunai = $newKasTunai->save();
    
                    if (!$newKasTunai) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;

                case "bagihasil":
                        $newBagiHasil = new KasTabBagiHasil();
                        $newBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                        $newBagiHasil->keterangan = $request->keterangan;
                        $newBagiHasil->saldo = $pengajuanBiaya->nominal;
                        $newBagiHasil->type = 'pengeluaran';
                        $newBagiHasil->pengajuan_biaya_id = $pengajuanBiaya->id;
                        $newBagiHasil = $newBagiHasil->save();
        
                        if (!$newBagiHasil) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
        
                        break;
                    case "nonbagihasil":
                        $newNonBagiHasil = new KasTabNonBagiHasil();
                        $newNonBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                        $newNonBagiHasil->keterangan = $request->keterangan;
                        $newNonBagiHasil->saldo = $pengajuanBiaya->nominal;
                        $newNonBagiHasil->type = 'pengeluaran';
                        $newNonBagiHasil->pengajuan_biaya_id = $pengajuanBiaya->id;
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
            
            switch ($kategoriCase) {
                case 1:
                    $newBPP = new BebanPengelolaandanPengembangan();
                    $newBPP->tanggal_transaksi = $request->tanggal_transaksi;
                    $newBPP->keterangan = $request->keterangan;
                    $newBPP->saldo = $pengajuanBiaya->nominal;
                    $newBPP->type = 'pemasukan';
                    $newBPP->pengajuan_biaya_id = $pengajuanBiaya->id;
                    $newBPP = $newBPP->save();

                    if (!$newBPP) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;
                case 2:
                    $newBagianNazhir = new BagianNazhir();
                    $newBagianNazhir->tanggal_transaksi = $request->tanggal_transaksi;
                    $newBagianNazhir->keterangan = $request->keterangan;
                    $newBagianNazhir->saldo = $pengajuanBiaya->nominal;
                    $newBagianNazhir->type = 'pemasukan';
                    $newBagianNazhir->pengajuan_biaya_id = $pengajuanBiaya->id;
                    $newBagianNazhir = $newBagianNazhir->save();

                    if (!$newBagianNazhir) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;

                case 3:
                    $newPentasyarufan = new PentasyarufanManfaat();
                    $newPentasyarufan->tanggal_transaksi = $request->tanggal_transaksi;
                    $newPentasyarufan->keterangan = $request->keterangan;
                    $newPentasyarufan->saldo = $pengajuanBiaya->nominal;                        
                    $newPentasyarufan->type = 'pemasukan';
                    $newPentasyarufan->pengajuan_biaya_id = $pengajuanBiaya->id;
                    $newPentasyarufan = $newPentasyarufan->save();

                    if (!$newPentasyarufan) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }
                    
                break;

                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPengajuanBiaya, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }

    }
    
    public function Pencairan(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();
            $pengajuanBiaya = PengajuanBiaya::find($id);

            
            $pencairan = $pengajuanBiaya->pencairan;
            
            DB::beginTransaction();

            if($pencairan==0)
            {
                $pengajuanBiaya->pencairan = 1;
                
            }

            $newPengajuanBiaya = $pengajuanBiaya->save();

                if (!$newPengajuanBiaya) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            DB::commit();

            return Response::HttpResponse(200, $newPengajuanBiaya, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }

    }

    public function DropdownKategoriBiaya(Request $request){
        //$kategori = ['BPP'=>'Beban pengelolaan dan pengembangan wakaf','bagiannazhir'=>'Bagian nazhir atas hasil pengelolaan dan pengembangan wakaf', 'pentasyarufan' => 'Pentasyarufan Manfaat Wakaf'];
        $kategori = DB::table('dropdown_kategori_biaya')->pluck("nama","id");
        return Response::HttpResponse(200, $kategori, "Success", true);
    }

    public function DropdownJenisBiaya(Request $request, $id){
        /* $jenis = [
            'BPP' => ['atk' => 'Beban ATK','pemasaran'=>'Beban Pemasaran','rapat'=>'Beban rapat','penyaluran'=>'Beban penyaluran manfaat wakaf','administrasi'=>'Beban Administrasi Bank','pajak'=>'Beban Pajak'],
            '2' => ['insentif'=>'Insentif Nazhir','tunjangan'=>'Tunjangan Kesehatan'],
            '3' => ['produktif'=>'Hibah Produktif','konsumtif'=>'Hibah Konsumtif']
        ]; */

        $jenis = DB::table("dropdown_jenis_biaya")->where("kategori_id",$id)->pluck("nama","id");

        return Response::HttpResponse(200, $jenis, "Success", true);
    }

    public function DropdownJenisBiayaDua(Request $request, $id){
        /* $jenis = [
            '1' => ['ekonomi' => 'Kegiatan Ekonomi Umat','kesejahteraan'=>'Kegiatan Kesejahteraan Umum']
        ]; */

        $kegiatanHibah = DB::table("dropdown_jenis_biaya_dua")->where("jenis_id",$id)->pluck("nama","id");

        return Response::HttpResponse(200, $kegiatanHibah, "Success", true);
    }

    public function DropdownJenisBiayaTiga(Request $request, $id){
        /* $jenis = [
            '1' => ['ibadah' => 'Kegiatan Ibadah','pendidikan'=>'Kegiatan Pendidikan','kesehatan'=>'Kegiatan Kesehatan','bantuan'=>'Kegiatan Bantuan Fakir, Miskin, dan Anak Terlantar']
        ]; */

        $kegiatanEkonomi = DB::table("dropdown_jenis_biaya_tiga")->where("kegiatan_id",$id)->pluck("nama","id");

        return Response::HttpResponse(200, $kegiatanEkonomi, "Success", true);
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

        $datas = PengajuanBiaya::where($inputs["search_type"],"like","%".$inputs["value"]."%")->paginate(10);
        return Response::HttpResponse(200, $datas, "OK", false);
    }
}