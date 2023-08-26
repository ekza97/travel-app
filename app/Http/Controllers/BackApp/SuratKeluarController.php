<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Helpers\Helper;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\DataTables\SuratKeluarDataTable;

class SuratKeluarController extends Controller
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
    public function index(SuratKeluarDataTable $dataTable)
    {
        return $dataTable->render('backapp.suratKeluar.index');
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
                'no_surat' => 'required|string|max:191',
                'tujuan' => 'required|string|max:191',
                'tanggal' => 'required|date',
                'alamat' => 'required|string|max:191',
                'perihal' => 'required|string|max:191',
                'keterangan' => 'required|string|max:191',
                'file' => 'required|max:2048|mimes:pdf'
            ]);

            $path = $request->file('file')->store('public/suratKeluar');

            SuratKeluar::create([
                'no_surat' => $request->no_surat,
                'tujuan' => $request->tujuan,
                'tanggal' => $request->tanggal,
                'alamat' => $request->alamat,
                'perihal' => $request->perihal,
                'keterangan' => $request->keterangan,
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
        $data = SuratKeluar::find($id);
        $linkUpdate = route("letter-out.update", Crypt::encrypt($data->id));
        $fileUrl = asset('storage/suratKeluar/'.Helper::getFilename($data->file));
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
                'no_surat' => 'string|max:191',
                'tujuan' => 'string|max:191',
                'tanggal' => 'date',
                'alamat' => 'string|max:191',
                'perihal' => 'string|max:191',
                'keterangan' => 'string|max:191',
                'file' => 'max:2048|mimes:pdf'
            ]);


            $db = SuratKeluar::find($id);

            if($request->hasFile('file')){
                Storage::delete($db->file);
                $path = $request->file('file')->store('public/suratKeluar');
            }else{
                $path = $db->file;
            }

            $db->no_surat = $request->no_surat;
            $db->tujuan = $request->tujuan;
            $db->tanggal = $request->tanggal;
            $db->alamat = $request->alamat;
            $db->perihal = $request->perihal;
            $db->keterangan = $request->keterangan;
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
        $data = SuratKeluar::find($id);
        Storage::delete($data->file);
        $data->delete();
        return true;
    }
}