<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Jamaah;
use App\Helpers\Helper;
use App\Models\CoverLetter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class CoverLetterController extends Controller
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
        $jamaahs = Jamaah::all();
        $last_no = CoverLetter::latest()->orderBy('id','desc')->value('number');
        return view('backapp.surat_pengantar.index',compact('jamaahs','last_no'));
    }

    public function table(Request $request)
    {
        $query = CoverLetter::orderBy('id','desc');

        return DataTables::of($query)
                        ->addIndexColumn()
                        ->addColumn('nik',function($row){
                            return $row->jamaahs->nik;
                        })
                        ->addColumn('fullname',function($row){
                            return $row->jamaahs->fullname;
                        })
                        ->addColumn('action', function($row){
                            $action = '';
                            if(Gate::allows('cetak cover-letter')){
                                $action .= '<a href="' . route("cover-letters.cetak", Crypt::encrypt($row->id)) . '" target="_blank" class="btn btn-sm btn-icon btn-outline-info me-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Cetak Surat">
                                    <i class="bi bi-printer"></i>
                                </a>';
                            }
                            if(Gate::allows('delete cover-letter')){
                            $action .= '<form method="post" action="' . route("cover-letters.destroy", Crypt::encrypt($row->id)) . '"
                                id="deleteCoverLetter" style="display:inline" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Hapus Data">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>';
                            }
                            return $action;
                        })
                        ->rawColumns(['nik','fullname','action'])->make(true);
    }

    public function cetak($id)
    {
        $id = Crypt::decrypt($id);
        $row = CoverLetter::find($id);
        return view('backapp.surat_pengantar.cetak',compact('row'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'jamaah_id' => 'required',
                'number' => 'required',
                'fullnumber' => 'required|string|max:191',
            ]);

            CoverLetter::create([
                'jamaah_id' => $request->jamaah_id,
                'number' => $request->number,
                'fullnumber' => $request->number.$request->fullnumber,
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decrypt($id);
        $data = CoverLetter::find($id)->delete();
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