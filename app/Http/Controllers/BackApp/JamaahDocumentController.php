<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Jamaah;
use App\Helpers\Helper;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\JamaahHasDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class JamaahDocumentController extends Controller
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
        $documents = Document::all();
        return view('backapp.document_jamaah.index',compact('jamaahs','documents'));
    }

    public function table(Request $request)
    {
        $query = JamaahHasDocument::with(['jamaahs','documents'])->orderBy('id','desc');

        if($request->jamaah){
            $query = $query->where('jamaah_id',$request->jamaah);
        }
        if($request->document){
            $query = $query->where('document_id',$request->document);
        }

        return DataTables::of($query)
                        ->addIndexColumn()
                        ->addColumn('nik_fullname',function($row){
                            return $row->jamaahs->nik.'<hr class="p-0 my-1 mx-0">'.$row->jamaahs->fullname;
                        })
                        ->addColumn('ttl_jk',function($row){
                            return $row->jamaahs->pob.', '.Helper::dateIndo($row->jamaahs->dob).'<hr class="p-0 my-1 mx-0">'.($row->jamaahs->gender=='L'?'Laki-Laki':'Perempuan');
                        })
                        ->addColumn('document',function($row){
                            return '<a href="'.asset('storage/jamaah_document/'.Helper::getFilename($row->file)).'" target="_blank">'.$row->documents->name.'</a>';
                        })
                        ->addColumn('action', function($row){
                            $action = '';
                            if(Gate::allows('download file')){
                                $action .= '<a href="' . route("jamaah-documents.download", Crypt::encrypt($row->id)) . '" target="_blank" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Unduh File">
                                    <i class="bi bi-download"></i>
                                </a>';
                            }
                            if(Gate::allows('delete file')){
                            $action .= '<form method="post" action="' . route("jamaah-documents.destroy", Crypt::encrypt($row->id)) . '"
                                id="deleteJamaahDocument" style="display:inline" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Hapus Data">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>';
                            }
                            return $action;
                        })
                        ->rawColumns(['nik_fullname','ttl_jk','document','action'])->make(true);
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
                'jamaah_id' => 'required',
                'document_id' => 'required',
                'file' => 'file|max:2048|mimes:pdf'
            ]);

            $cek = Helper::jamaahFileUploaded($request->jamaah_id,$request->document_id);

            if($cek==null){
                JamaahHasDocument::create([
                    'jamaah_id' => $request->jamaah_id,
                    'document_id' => $request->document_id,
                    'file' => $request->file('file')->store('public/jamaah_document'),
                    'created_by' => Auth::id()
                ]);
            }else{
                File::delete('storage/jamaah_document/'.Helper::getFilename($cek->file));
                $cek->jamaah_id = $request->jamaah_id;
                $cek->document_id = $request->document_id;
                $cek->file = $request->file('file')->store('public/jamaah_document');
                $cek->updated_by = Auth::id();
                $cek->update();
            }

            return response()->json([
                'status' => true, 'message' => 'Berhasil unggah dokumen'
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decrypt($id);
        $data = JamaahHasDocument::find($id);
        if($data->file!=null){
            File::delete('storage/jamaah_document/'.Helper::getFilename($data->file));
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

    public function download(string $id)
    {
        $id = Crypt::decrypt($id);
        $data = JamaahHasDocument::with(['jamaahs','documents'])->find($id);
        if($data->file==null){
            $path = 'no-image.jpeg';
        }else{
            $path = '/storage/jamaah_document/'.Helper::getFilename($data->file);
        }
        $ext = pathinfo($path,PATHINFO_EXTENSION);
        $filename = $data->documents->name.'_'.$data->jamaahs->fullname.'.'.$ext;
        return response()->download(public_path($path),$filename);
    }
}