<?php

namespace App\Http\Controllers\BackApp\MasterData;

use Exception;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\ScheduleDataTable;
use App\Models\Packet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ScheduleDataTable $dataTable)
    {
        $masterData = true;
        return $dataTable->render('backapp.masterData.schedule.index',compact('masterData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterData = true;
        $packet = Packet::all();
        return view('backapp.masterData.schedule.create',compact('masterData','packet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:191',
                'start_date' => 'required|date',
                'day' => 'required|string|max:191',
            ]);

            $date = Carbon::createFromFormat('Y-m-d',$request->start_date);
            $end_date = $date->addDays($request->day);

            Schedule::create([
                'packet_id' => $request->packet_id,
                'name' => ucwords($request->name),
                'start_date' => $request->start_date,
                'end_date' => $end_date,
                'day' => $request->day,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'status' => true, 'message' => 'Berhasil tersimpan'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = Crypt::decrypt($id);
        $masterData = true;
        $packet = Packet::all();
        $edit = Schedule::find($id);
        return view('backapp.masterData.schedule.edit',compact('masterData','packet','edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|string|max:191',
                'start_date' => 'required|date',
                'day' => 'required|string|max:191',
            ]);

            $date = Carbon::createFromFormat('Y-m-d',$request->start_date);
            $end_date = $date->addDays($request->day);

            $db = Schedule::find($id);

            $db->packet_id = $request->packet_id;
            $db->name = ucwords($request->name);
            $db->start_date = $request->start_date;
            $db->end_date = $end_date;
            $db->day = $request->day;
            $db->description = $request->description;
            $db->updated_by = Auth::id();

            $db->update();

            return response()->json([
                'status' => true, 'message' => 'Berhasil update'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decrypt($id);
        $data = Schedule::find($id)->delete();
        if($data){
            return response()->json([
                'code' => 200, 'message' => 'Berhasil hapus'
            ]);
        }else{
            return response()->json([
                'code' => 400, 'message' => 'Tidak Bisa Dihapus'
            ]);
        }
    }
}