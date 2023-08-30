<?php

namespace App\Http\Controllers;

use App\Charts\JamaahChart;
use App\Models\Agent;
use App\Models\Jamaah;
use App\Models\Packet;
use App\Models\User;
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
    public function index(JamaahChart $jamaahChart)
    {
        $dateNow = Carbon::now()->translatedFormat('l, d F Y');
        $t_packet = Packet::count();
        $t_agent = Agent::count();
        $t_jamaah = Jamaah::count();
        $t_users = User::count();
        return view('backapp.home',[
            'dateNow'=>$dateNow,
            't_packet'=>$t_packet,
            't_agent'=>$t_agent,
            't_jamaah'=>$t_jamaah,
            't_users'=>$t_users,
            'pieChart'=>$jamaahChart->buildPie(),
            'radialChart'=>$jamaahChart->buildRadial()
        ]);
    }
}