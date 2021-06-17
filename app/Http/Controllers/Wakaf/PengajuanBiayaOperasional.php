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
                'kategori_biaya' => 'required',
                'jenis_biaya' => 'required',
                'keterangan_biaya' => 'required|string|max:255',
                'nominal' => 'required|numeric',
                'sumber_biaya' => 'required|in:tunai,bagihasil,nonbagihasil',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $pengajuanBiaya = new PengajuanBiaya();

            $pengajuanBiaya->nama_pengaju = $request->nama_pengaju;
            $pengajuanBiaya->kategori_biaya = $request->kategori_biaya;
            $pengajuanBiaya->jenis_biaya = $request->jenis_biaya;
            $pengajuanBiaya->keterangan_biaya = $request->keterangan_biaya;
            $pengajuanBiaya->nominal = $request->nominal;
            $pengajuanBiaya->sumber_biaya = $request->sumber_biaya;
            $pengajuanBiaya->created_by = $this->admin->name;
            $pengajuanBiaya->modified_by = $this->admin->name;

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
            $pengajuanBiaya = PengajuanBiaya::select('nama_pengaju','kategori_biaya','jenis_biaya','keterangan_biaya','nominal','sumber_biaya')
            ->where('id',$id)->get();
            return Response::HttpResponse(200, $pengajuanBiaya, "Info User yang akan diedit berhasil ditampilkan", false);
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
                'kategori_biaya' => 'required',
                'jenis_biaya' => 'required',
                'keterangan_biaya' => 'required|string|max:255',
                'nominal' => 'required|numeric',
                'sumber_biaya' => 'required|in:tunai,bagihasil,nonbagihasil',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $pengajuanBiaya = PengajuanBiaya::find($id);

            $pengajuanBiaya->nama_pengaju = $request->nama_pengaju;
            $pengajuanBiaya->kategori_biaya = $request->kategori_biaya;
            $pengajuanBiaya->jenis_biaya = $request->jenis_biaya;
            $pengajuanBiaya->keterangan_biaya = $request->keterangan_biaya;
            $pengajuanBiaya->nominal = $request->nominal;
            $pengajuanBiaya->sumber_biaya = $request->sumber_biaya;
            $pengajuanBiaya->created_by = $this->admin->name;
            $pengajuanBiaya->modified_by = $this->admin->name;

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

            $currData->deleted_by = $this->admin->name;

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
                $pengajuanBiaya->approval = '1';
                $pengajuanBiaya->status_persetujuan = 'approved';
                
                $pengajuanBiaya->approved_at = \Carbon\Carbon::now();
                $pengajuanBiaya->approved_by = $this->admin->name;
                
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
    
    public function Pencairan(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();
            $pengajuanBiaya = PengajuanBiaya::find($id);

            $kategoriBiaya = $pengajuanBiaya->kategori_biaya;
            $sumberBiaya = $pengajuanBiaya->sumber_biaya;
            $pencairan = $pengajuanBiaya->pencairan;
            
            DB::beginTransaction();

            if($pencairan=='0')
            {
                $pengajuanBiaya->pencairan = '1';
                
            }

            $newPengajuanBiaya = $pengajuanBiaya->save();

                if (!$newPengajuanBiaya) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            switch ($sumberBiaya) {
                case "tunai":
                $newKasTunai = new KasTunai();
                $newKasTunai->tanggal_transaksi = $pengajuanBiaya->approved_at;
                $newKasTunai->keterangan = 'Pencairan Pengajuan Biaya';
                $newKasTunai->saldo = $pengajuanBiaya->nominal;
                $newKasTunai->type = 'kredit';
                $newKasTunai->pengajuan_biaya_id = $pengajuanBiaya->id;
                $newKasTunai = $newBagiHasil->save();
    
                    if (!$newKasTunai) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;

                case "bagihasil":
                        $newBagiHasil = new KasTabBagiHasil();
                        $newBagiHasil->tanggal_transaksi = $pengajuanBiaya->approved_at;
                        $newBagiHasil->keterangan = 'Pencairan Pengajuan Biaya';
                        $newBagiHasil->saldo = $pengajuanBiaya->nominal;
                        $newBagiHasil->type = 'kredit';
                        $newBagiHasil->pengajuan_biaya_id = $pengajuanBiaya->id;
                        $newBagiHasil = $newBagiHasil->save();
        
                        if (!$newBagiHasil) {
                            DB::rollBack();
                            return Response::HttpResponse(400, null, "Failed to create data ", true);
                        }
        
                        break;
                    case "nonbagihasil":
                        $newNonBagiHasil = new KasTabNonBagiHasil();
                        $newNonBagiHasil->tanggal_transaksi = $pengajuanBiaya->approved_at;
                        $newNonBagiHasil->keterangan = 'Pencairan Pengajuan Biaya';
                        $newNonBagiHasil->saldo = $pengajuanBiaya->nominal;
                        $newNonBagiHasil->type = 'kredit';
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
            
            switch ($kategoriBiaya) {
                case "1":
                    $newBPP = new BebanPengelolaandanPengembangan();
                    $newBPP->tanggal_transaksi = $pengajuanBiaya->approved_at;
                    $newBPP->keterangan = 'Pencairan Penyaluran Manfaat';
                    $newBPP->saldo = $pengajuanBiaya->nominal;
                    $newBPP->type = 'debit';
                    $newBPP->pengajuan_biaya_id = $pengajuanBiaya->id;
                    $newBPP = $newBPP->save();

                    if (!$newBPP) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;
                case "2":
                    $newBagianNazhir = new BagianNazhir();
                    $newBagianNazhir->tanggal_transaksi = $pengajuanBiaya->approved_at;
                    $newBagianNazhir->keterangan = 'Pencairan Penyaluran Manfaat';
                    $newBagianNazhir->saldo = $pengajuanBiaya->nominal;
                    $newBagianNazhir->type = 'debit';
                    $newBagianNazhir->pengajuan_biaya_id = $pengajuanBiaya->id;
                    $newBagianNazhir = $newBagianNazhir->save();

                    if (!$newBagianNazhir) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                break;

                case "3":
                    $newPentasyarufan = new PentasyarufanManfaat();
                    $newPentasyarufan->tanggal_transaksi = $pengajuanBiaya->approved_at;
                    $newPentasyarufan->keterangan = 'Pencairan Penyaluran Manfaat';
                    $newPentasyarufan->saldo = $pengajuanBiaya->nominal;                        
                    $newPentasyarufan->type = 'debit';
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