<?php

namespace App\Http\Controllers\BackApp\DataMaster;

use Exception;
use App\Models\MasterSurat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\MasterSuratDataTable;

class SuratController extends Controller
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
    public function index(MasterSuratDataTable $dataTable)
    {
        $masterData = true;
        return $dataTable->render('backapp.masterData.surat.index',compact('masterData'));
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
                'code' => 'required|string|max:191',
                'name' => 'required|string|max:191',
            ]);

            MasterSurat::create([
                'code' => strtoupper($request->code),
                'name' => ucwords($request->name),
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
        $data = MasterSurat::find($id);
        $linkUpdate = route("letters.update", Crypt::encrypt($data->id));
        return response()->json(['code' => $data->code, 'name' => $data->name, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'code' => 'required|string|max:191',
                'name' => 'required|string|max:191'
            ]);

            $db = MasterSurat::find($id);

            $db->code = strtoupper($request->code);
            $db->name = ucwords($request->name);

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
        $data = MasterSurat::where('id', $id);
        $data->delete();
        return true;
    }
}