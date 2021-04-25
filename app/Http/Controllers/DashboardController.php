<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function totalPenerimaanWakaf()
    {
        /* $penerimaan = Penerimaan::count();
        $response = [
            "success" => true,
            "message" => "Users retrieved successfully.",
            "data" => $penerimaan
            ];
        return response($response, 200); */
    }
    
    public function totalWakif()
    {
        /* $wakif = Wakif::count();
        $response = [
            "success" => true,
            "message" => "Users retrieved successfully.",
            "data" => $wakif
            ];
        return response($response, 200); */
    }

    public function totalPenerimaManfaat()
    {
        /* $penerima = PenerimaManfaat::count();
        $response = [
            "success" => true,
            "message" => "Users retrieved successfully.",
            "data" => $penerima
            ];
        return response($response, 200); */
    }

    public function chartPenerimaan()
    {
        $sum = Model::sum('sum_field');
        $users = User::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');

        //return view('chart', compact('users'));
    }

    public function chartPenyaluran()
    {
        $sum = Model::sum('sum_field');
        $users = User::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');

        //return view('chart', compact('users'));
    }

    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(10);
        //return view('users.index', compact('users'));
        $response = [
            "success" => true,
            "message" => "Users retrieved successfully.",
            "data" => $user
            ];
        return response($response, 200);
    }
}
