<?php

namespace App\Http\Controllers;

use App\Charts\SuratChart;
use App\Models\Agent;
use App\Models\Packet;
use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(SuratChart $suratChart)
    {
        $dateNow = Carbon::now()->translatedFormat('l, d F Y');
        $t_suratMasuk = Packet::count();
        $t_suratKeluar = Agent::count();
        $t_users = User::count();
        return view('backapp.home',[
            'dateNow'=>$dateNow,
            't_suratMasuk'=>$t_suratMasuk,
            't_suratKeluar'=>$t_suratKeluar,
            't_users'=>$t_users,
            'pieChart'=>$suratChart->buildPie(),
            'radialChart'=>$suratChart->buildRadial()
        ]);
    }
}