<?php

namespace App\Exports;

use App\Models\Jamaah;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JamaahExport implements FromView
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }


    public function view(): View
    {
        // dd($this->request);
        $query = Jamaah::query()->with(['categories','agents','schedules'])->orderBy('id','desc');
        if($this->request->category!=null){
            $query->where('category_id',$this->request->category);
        }
        if($this->request->agent!=null){
            $query->where('agent_id',$this->request->agent);
        }
        if($this->request->schedule!=null){
            $query->where('schedule_id',$this->request->schedule);
        }
        return view('backapp.exports.jamaah', [
            'jamaah' => $query->get()
        ]);
    }
}