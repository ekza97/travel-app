<?php

namespace App\Http\Controllers\BackApp\MasterData;

use Exception;
use App\Models\Hotel;
use Illuminate\Http\Request;
use App\DataTables\HotelDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class HotelController extends Controller
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
    public function index(HotelDataTable $dataTable)
    {
        $masterData = true;
        return $dataTable->render('backapp.masterData.hotel.index',compact('masterData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:191',
                'type' => 'required|string|max:191',
            ]);

            Hotel::create([
                'name' => ucwords($request->name),
                'type' => $request->type,
                'location' => $request->location,
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
        $data = Hotel::find($id);
        $linkUpdate = route("hotels.update", Crypt::encrypt($data->id));
        return response()->json(['name' => $data->name, 'type' => $data->type, 'location' => $data->location, 'link' => $linkUpdate]);
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
                'type' => 'required|string|max:191',
            ]);

            $db = Hotel::find($id);

            $db->name = ucwords($request->name);
            $db->type = $request->type;
            $db->location = $request->location;
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
        $data = Hotel::find($id)->delete();
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