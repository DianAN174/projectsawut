<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\ModelPengelolaan\Pengelolaan;
use App\Models\ModelPengelolaan\Kas;
use App\Models\ModelPengelolaan\KasTunai;
use App\Models\ModelPengelolaan\KasTabWakaf;
use App\Models\ModelPengelolaan\KasTabBagiHasil;
use App\Models\ModelPengelolaan\KasTabNonbagiHasil;
use App\Models\ModelPengelolaan\KasDepositoWakaf;
use App\Models\User;
use App\Utils\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;

class PengelolaanWakaf
{
    protected $admin;

    public function __construct(User $user)
    {
        $this->admin = $user;
    }

    public function PindahSaldo(Request $request){

        $this->admin = $request->user();

        $validator = Validator::make($request->all(), [
            'akun_asal' => 'required|in:tunai,tabwakaf,tabbagihasil,tabnonbagihasil,deposito',
            'akun_tujuan' => 'required|in:tunai,tabwakaf,tabbagihasil,tabnonbagihasil,deposito',
            'saldo' => 'required|numeric',
            /* 'keterangan' => 'required|max:255',
            'tanggal_transaksi' => 'required|date_format:Y-m-d', */
        ]);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()->all()];
            return Response::HttpResponse(422, $response, "Invalid Data", false);
        }

        DB::beginTransaction();

        $pengelolaan = new Pengelolaan();
        
        $pengelolaan->akun_asal = $request->akun_asal;
        $pengelolaan->akun_tujuan = $request->akun_tujuan;
        $pengelolaan->saldo = $request->saldo;
        $pengelolaan->created_by = $this->admin->name;
        $pengelolaan->modified_by = $this->admin->name;

        $newPengelolaan = $pengelolaan->save();

        if (!$newPengelolaan) {
            DB::rollBack();
            return Response::HttpResponse(400, null, "Failed to create data", true);
        }

        $akunAsal = $pengelolaan->akun_asal;
        
            switch ($akunAsal) {
                case "tunai":
                    $newTunai = new KasTunai();
                    $newTunai->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTunai->keterangan = $request->keterangan;
                    $newTunai->saldo = $pengelolaan->saldo;
                    $newTunai->type = 'pengeluaran';
                    $newTunai->pengelolaan_id = $pengelolaan->id;
                    $newTunai = $newTunai->save();

                    if (!$newTunai) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "tabwakaf":
                    $newTabWakaf = new KasTabWakaf();
                    $newTabWakaf->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTabWakaf->keterangan = $request->keterangan;
                    $newTabWakaf->saldo = $pengelolaan->saldo;
                    $newTabWakaf->type = 'pengeluaran';
                    $newTabWakaf->pengelolaan_id = $pengelolaan->id;
                    $newTabWakaf = $newTabWakaf->save();

                    if (!$newTabWakaf) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "tabbagihasil":
                    $newTabBagiHasil = new KasTabBagiHasil();
                    $newTabBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTabBagiHasil->keterangan = $request->keterangan;
                    $newTabBagiHasil->saldo = $pengelolaan->saldo;
                    $newTabBagiHasil->type = 'pengeluaran';
                    $newTabBagiHasil->pengelolaan_id = $pengelolaan->id;
                    $newTabBagiHasil = $newTabBagiHasil->save();

                    if (!$newTabBagiHasil) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "tabnonbagihasil":
                    $newTabNonBagiHasil = new KasTabNonBagiHasil();
                    $newTabNonBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTabNonBagiHasil->keterangan = $request->keterangan;
                    $newTabNonBagiHasil->saldo = $pengelolaan->saldo;
                    $newTabNonBagiHasil->type = 'pengeluaran';
                    $newTabNonBagiHasil->pengelolaan_id = $pengelolaan->id;;
                    $newTabNonBagiHasil = $newTabNonBagiHasil->save();

                    if (!$newTabNonBagiHasil) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "deposito":
                    $newDeposito = new KasDepositoWakaf();
                    $newDeposito->tanggal_transaksi = $request->tanggal_transaksi;
                    $newDeposito->keterangan = $request->keterangan;
                    $newDeposito->saldo = $pengelolaan->saldo;
                    $newDeposito->type = 'pengeluaran';
                    $newDeposito->pengelolaan_id = $pengelolaan->id;;
                    $newDeposito = $newDeposito->save();

                    if (!$newDeposito) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
            }

        $akunTujuan = $pengelolaan->akun_tujuan;

            switch ($akunTujuan) {
                case "tunai":
                    $newTunai = new KasTunai();
                    $newTunai->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTunai->keterangan = $request->keterangan;
                    $newTunai->saldo = $pengelolaan->saldo;
                    $newTunai->type = 'pemasukan';
                    $newTunai->pengelolaan_id = $pengelolaan->id;
                    $newTunai = $newTunai->save();

                    if (!$newTunai) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "tabwakaf":
                    $newTabWakaf = new KasTabWakaf();
                    $newTabWakaf->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTabWakaf->keterangan = $request->keterangan;
                    $newTabWakaf->saldo = $pengelolaan->saldo;
                    $newTabWakaf->type = 'pemasukan';
                    $newTabWakaf->pengelolaan_id = $pengelolaan->id;
                    $newTabWakaf = $newTabWakaf->save();

                    if (!$newTabWakaf) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "tabbagihasil":
                    $newTabBagiHasil = new KasTabBagiHasil();
                    $newTabBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTabBagiHasil->keterangan = $request->keterangan;
                    $newTabBagiHasil->saldo = $pengelolaan->saldo;
                    $newTabBagiHasil->type = 'pemasukan';
                    $newTabBagiHasil->pengelolaan_id = $pengelolaan->id;
                    $newTabBagiHasil = $newTabBagiHasil->save();

                    if (!$newTabBagiHasil) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "tabnonbagihasil":
                    $newTabNonBagiHasil = new KasTabNonBagiHasil();
                    $newTabNonBagiHasil->tanggal_transaksi = $request->tanggal_transaksi;
                    $newTabNonBagiHasil->keterangan = $request->keterangan;
                    $newTabNonBagiHasil->saldo = $pengelolaan->saldo;
                    $newTabNonBagiHasil->type = 'pemasukan';
                    $newTabNonBagiHasil->pengelolaan_id = $pengelolaan->id;
                    $newTabNonBagiHasil = $newTabNonBagiHasil->save();

                    if (!$newTabNonBagiHasil) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                case "deposito":
                    $newDeposito = new KasDepositoWakaf();
                    $newDeposito->tanggal_transaksi = $request->tanggal_transaksi;
                    $newDeposito->keterangan = $request->keterangan;
                    $newDeposito->saldo = $pengelolaan->saldo;
                    $newDeposito->type = 'pemasukan';
                    $newDeposito->pengelolaan_id = $pengelolaan->id;
                    $newDeposito = $newDeposito->save();

                    if (!$newDeposito) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data ", true);
                    }

                    break;

                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newPengelolaan, "Success", false);
    }

    public function Index(Request $request)
    {
        try
        {
            $sumKasTunai = KasTunai::sum('saldo');
            $sumKreditTunai = KasTunai::where('type','pengeluaran')->sum('saldo');
            $saldoTerakhirKasTunai=$sumKasTunai - $sumKreditTunai;

            //ktw=kas tabungan wakaf
            $sum_ktw = KasTabWakaf::sum('saldo');
            $sumKredit_ktw = KasTabWakaf::where('type','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktw=$sum_ktw - $sumKredit_ktw;

            //ktbh=kas tabungan bagi hasil
            $sum_ktbh = KasTabBagiHasil::sum('saldo');
            $sumKredit_ktbh = KasTabBagiHasil::where('type','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbh=$sum_ktbh - $sumKredit_ktbh;

            //ktnbh=kas tabungan non bagi hasil
            $sum_ktbnh = KasTabNonBagiHasil::sum('saldo');
            $sumKredit_ktbnh = KasTabNonBagiHasil::where('type','pengeluaran')->sum('saldo');
            $saldoTerakhir_ktbnh = $sum_ktbnh - $sumKredit_ktbnh;

            //kdw = kas deposito wakaf
            $sum_kdw = KasDepositoWakaf::sum('saldo');
            $sumKredit_kdw = KasDepositoWakaf::where('type','pengeluaran')->sum('saldo');
            $saldoTerakhir_kdw = $sum_kdw - $sumKredit_kdw;

            $saldo_terakhir_kas = [$saldoTerakhirKasTunai, $saldoTerakhir_ktw, $saldoTerakhir_ktbh, $saldoTerakhir_ktbnh, $saldoTerakhir_kdw];

            
            for ($i=1; $i<=Kas::count('id'); $i++) {
            $newKasTable = Kas::where('id',$i)->update([
                    'saldo' => $saldo_terakhir_kas[$i-1],
     
            ]);
            }
            
            if (!$newKasTable) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to display saldo", true);
            }

            /* $kasTable = Kas::find(1);
            $kasTable->saldo = $saldoTerakhirKasTunai;
            $newKasTable = $kasTable->save();
            
            $kasTable = Kas::find(2);
            $kasTable->saldo = $saldoTerakhir_ktw;
            $newKasTable = $kasTable->save();

            $kasTable = Kas::find(3);
            $kasTable->saldo = $saldoTerakhir_ktbh;
            $newKasTable = $kasTable->save();

            $kasTable = Kas::find(4);
            $kasTable->saldo = $saldoTerakhir_ktbnh;
            $newKasTable = $kasTable->save();

            $kasTable = Kas::find(5);
            $kasTable->saldo = $saldoTerakhir_kdw;
            $newKasTable = $kasTable->save();

             */

            $datas = Kas::all();
                
            return Response::HttpResponse(200, $datas, "Success", false);
        
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }
}
