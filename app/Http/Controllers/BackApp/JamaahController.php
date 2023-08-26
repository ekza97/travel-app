<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Agent;
use App\Models\Jamaah;
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\JamaahHasDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class JamaahController extends Controller
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
    public function index()
    {
        $categories = Category::all();
        $agents = Agent::all();
        $schedules = Schedule::all();
        // return $dataTable->render('backapp.jamaah.index',compact('categories','agents','schedules'));
        return view('backapp.jamaah.index',compact('categories','agents','schedules'));
    }

    public function table(Request $request)
    {
        $query = Jamaah::orderBy('id','desc');

        if($request->category){
            $query = $query->where('category_id',$request->category);
        }
        if($request->agent){
            $query = $query->where('agent_id',$request->agent);
        }
        if($request->schedule){
            $query = $query->where('schedule_id',$request->schedule);
        }

        return DataTables::of($query)
                        ->addIndexColumn()
                        ->addColumn('agent_jadwal',function($row){
                            return $row->agents->fullname.'<hr class="p-0 my-1 mx-0">'.$row->schedules->name;
                        })
                        ->addColumn('nik_fullname',function($row){
                            return $row->nik.'<hr class="p-0 my-1 mx-0">'.$row->fullname;
                        })
                        ->addColumn('ttl_jk',function($row){
                            return $row->pob.', '.Helper::dateIndo($row->dob).'<hr class="p-0 my-1 mx-0">'.($row->gender=='L'?'Laki-Laki':'Perempuan');
                        })
                        ->addColumn('hp_status',function($row){
                            return $row->phone.'<hr class="p-0 my-1 mx-0">'.$row->martial_status;
                        })
                        ->addColumn('action', function($row){
                            $action = '';
                            if(Gate::allows('edit jamaah')){
                                $action .= '<a href="' . route("jamaahs.edit", Crypt::encrypt($row->id)) . '" class="btn btn-sm btn-icon btn-outline-info me-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Detail Data">
                                    <i class="bi bi-eye"></i>
                                </a>';
                            }
                            if(Gate::allows('dokumen jamaah')){
                                $action .= '<a href="' . route("jamaahs.documents", Crypt::encrypt($row->id)) . '" class="btn btn-sm btn-icon btn-outline-secondary me-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Unggah Dokumen">
                                    <i class="bi bi-upload"></i>
                                </a>';
                            }
                            if(Gate::allows('edit jamaah')){
                                $action .= '<a href="' . route("jamaahs.edit", Crypt::encrypt($row->id)) . '" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>';
                            }
                            if(Gate::allows('delete jamaah')){
                            $action .= '<form method="post" action="' . route("jamaahs.destroy", Crypt::encrypt($row->id)) . '"
                                id="deleteJamaah" style="display:inline" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Hapus Data">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>';
                            }
                            return $action;
                        })
                        ->rawColumns(['agent_jadwal','nik_fullname','ttl_jk','hp_status','action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $agents = Agent::all();
        $schedules = Schedule::all();
        return view('backapp.jamaah.create',compact('categories','agents','schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required',
                'agent_id' => 'required',
                'schedule_id' => 'required',
                'nik' => 'required|string|max:16',
                'fullname' => 'required|string|max:191',
                'pob' => 'required|string|max:191',
                'dob' => 'required|date',
                'gender' => 'required',
                'martial_status' => 'required',
                'phone' => 'required|string|max:12',
                'profession' => 'required|string|max:191',
                'address' => 'required|string',
            ]);


            Jamaah::create([
                'category_id' => $request->category_id,
                'agent_id' => $request->agent_id,
                'schedule_id' => $request->schedule_id,
                'nik' => $request->nik,
                'fullname' => ucwords($request->fullname),
                'pob' => ucwords($request->pob),
                'dob' => $request->dob,
                'gender' => $request->gender,
                'martial_status' => $request->martial_status,
                'phone' => $request->phone,
                'profession' => $request->profession,
                'address' => $request->address,
                'heir_name' => $request->heir_name,
                'heir_relation' => $request->heir_relation,
                'heir_phone' => $request->heir_phone,
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
        $categories = Category::all();
        $agents = Agent::all();
        $schedules = Schedule::all();
        $edit = Jamaah::find($id);
        return view('backapp.jamaah.edit',compact('categories','agents','schedules','edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'category_id' => 'required',
                'agent_id' => 'required',
                'schedule_id' => 'required',
                'nik' => 'required|string|max:16',
                'fullname' => 'required|string|max:191',
                'pob' => 'required|string|max:191',
                'dob' => 'required|date',
                'gender' => 'required',
                'martial_status' => 'required',
                'phone' => 'required|string|max:12',
                'profession' => 'required|string|max:191',
                'address' => 'required|string',
            ]);

            $db = Jamaah::find($id);

            $db->category_id = $request->category_id;
            $db->agent_id = $request->agent_id;
            $db->schedule_id = $request->schedule_id;
            $db->nik = $request->nik;
            $db->fullname = ucwords($request->fullname);
            $db->pob = ucwords($request->pob);
            $db->dob = $request->dob;
            $db->gender = $request->gender;
            $db->martial_status = $request->martial_status;
            $db->phone = $request->phone;
            $db->profession = $request->profession;
            $db->address = $request->address;
            $db->heir_name = $request->heir_name;
            $db->heir_relation = $request->heir_relation;
            $db->heir_phone = $request->heir_phone;
            $db->created_by = Auth::id();

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
        $data = Jamaah::find($id)->delete();
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

    public function document(string $id)
    {
        $id = Crypt::decrypt($id);
        $jamaah = Jamaah::find($id);
        $doc = Document::all();
        return view('backapp.jamaah.document',compact('jamaah','doc'));
    }

    public function document_store(Request $request)
    {
        try {
            $request->validate([
                'jamaah_id' => 'required',
                'document_id' => 'required',
                'file' => 'file|max:2048|mimes:pdf'
            ]);

            JamaahHasDocument::create([
                'jamaah_id' => $request->jamaah_id,
                'document_id' => $request->document_id,
                'file' => $request->file('file')->store('public/jamaah_document'),
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'status' => true, 'message' => 'Berhasil tersimpan'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}