<?php

namespace App\Http\Controllers\BackApp\MasterData;

use Exception;
use App\Models\Agent;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\DataTables\AgentDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

class AgentController extends Controller
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
    public function index(AgentDataTable $dataTable)
    {
        $masterData = true;
        return $dataTable->render('backapp.masterData.agent.index',compact('masterData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterData = true;
        return view('backapp.masterData.agent.create',compact('masterData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string|max:191|unique:agents,token',
                'fullname' => 'required|string|max:191',
                'pob' => 'required|string|max:191',
                'dob' => 'required|date',
                'phone' => 'required|string|max:191',
                'address' => 'required|string',
                'image' => 'max:2048|mimes:png,jpg,jpeg',
                'is_active' => 'required'
            ]);

            if($request->file('image')){
                $image = $request->file('image')->store('public/agent');
            }else{
                $image = null;
            }

            Agent::create([
                'token' => strtoupper($request->token),
                'fullname' => ucwords($request->fullname),
                'pob' => ucwords($request->pob),
                'dob' => $request->dob,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $image,
                'is_active' => $request->is_active,
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
        $edit = Agent::find($id);
        $masterData = true;
        return view('backapp.masterData.agent.edit',compact('masterData','edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'token' => 'required|string|max:191|unique:agents,token,'.$id,
                'fullname' => 'required|string|max:191',
                'pob' => 'required|string|max:191',
                'dob' => 'required|date',
                'phone' => 'required|string|max:191',
                'address' => 'required|string',
                'image' => 'max:2048|mimes:png,jpg,jpeg',
                'is_active' => 'required'
            ]);

            $db = Agent::find($id);

            if($request->file('image')){
                if($db->image!=null){
                    File::delete('storage/agent/'.Helper::getFilename($db->image));
                }
                $image = $request->file('image')->store('public/agent');
            }else{
                $image = null;
            }

            $db->token = strtoupper($request->token);
            $db->fullname = ucwords($request->fullname);
            $db->pob = ucwords($request->pob);
            $db->dob = $request->dob;
            $db->phone = $request->phone;
            $db->address = $request->address;
            $db->image = $image;
            $db->is_active = $request->is_active;
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
        $data = Agent::find($id);
        if($data->image!=null){
            File::delete('storage/agent/'.Helper::getFilename($data->image));
        }
        if($data->delete()){
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
