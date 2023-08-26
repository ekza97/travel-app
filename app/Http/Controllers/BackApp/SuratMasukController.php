<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Helpers\Helper;
use App\Models\SuratMasuk;
use App\Models\MasterSurat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\SuratMasukDataTable;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
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
    public function index(SuratMasukDataTable $dataTable)
    {
        return $dataTable->render('backapp.suratMasuk.index');
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
                'jenis_surat_id' => 'required|string|max:191',
                'tujuan' => 'required|string|max:191',
                'no_surat' => 'required|string|max:191',
                'alamat' => 'required|string|max:191',
                'tanggal' => 'required|date',
                'keterangan' => 'required|string|max:191',
                'perihal' => 'required|string|max:191',
                'file' => 'required|max:2048|mimes:pdf'
            ]);

            $path = $request->file('file')->store('public/suratMasuk');

            SuratMasuk::create([
                'jenis_surat_id' => $request->jenis_surat_id,
                'tujuan' => $request->tujuan,
                'no_surat' => $request->no_surat,
                'alamat' => $request->alamat,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'perihal' => $request->perihal,
                'file' => $path,
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
        $data = SuratMasuk::with('jenis_surat')->find($id);
        $linkUpdate = route("letter-in.update", Crypt::encrypt($data->id));
        $fileUrl = asset('storage/suratMasuk/'.Helper::getFilename($data->file));
        return response()->json(['data' => $data, 'link' => $linkUpdate, 'file_url'=>$fileUrl]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $request->validate([
                'jenis_surat_id' => 'string|max:191',
                'tujuan' => 'string|max:191',
                'no_surat' => 'string|max:191',
                'alamat' => 'string|max:191',
                'tanggal' => 'date',
                'keterangan' => 'string|max:191',
                'perihal' => 'string|max:191',
                'file' => 'max:2048|mimes:pdf'
            ]);


            $db = SuratMasuk::find($id);

            if($request->hasFile('file')){
                Storage::delete($db->file);
                $path = $request->file('file')->store('public/suratMasuk');
            }else{
                $path = $db->file;
            }

            $db->jenis_surat_id = $request->jenis_surat_id;
            $db->tujuan = $request->tujuan;
            $db->no_surat = $request->no_surat;
            $db->alamat = $request->alamat;
            $db->tanggal = $request->tanggal;
            $db->keterangan = $request->keterangan;
            $db->perihal = $request->perihal;
            $db->file = $path;

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
        $data = SuratMasuk::find($id);
        Storage::delete($data->file);
        $data->delete();
        return true;
    }

    public function selectJenisSurat(Request $request){
        $data = MasterSurat::select("code","name", "id")
            ->where('code', 'LIKE', '%' . $request->get('q') . '%')
            ->orWhere('name', 'LIKE', '%' . $request->get('q') . '%')
            ->orderBy('id','desc')
            ->paginate(5);

        $opt = [];
        foreach($data as $item){
            $opt[] = [
                'id'=>$item->id,
                'text'=>$item->code.' - '.$item->name,
            ];
        }
        return response()->json($opt);
    }
}