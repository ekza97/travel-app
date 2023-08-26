<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Jamaah;
use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
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
        return view('backapp.payment.index',compact('jamaahs'));
    }

    public function table(Request $request)
    {
        $query = Payment::with(['jamaahs'])->orderBy('id','desc');

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
                        ->addColumn('date',function($row){
                            return Helper::dateIndo($row->date);
                        })
                        ->addColumn('pay',function($row){
                            if($row->file==null){
                                $link = asset('no-image.jpeg');
                            }else{
                                $link = asset('storage/payment/'.Helper::getFilename($row->file));
                            }
                            return '<a href="'.$link.'" target="_blank">'.Helper::money($row->pay).'</a>';
                        })
                        ->addColumn('amount',function($row){
                            return Helper::money($row->amount);
                        })
                        ->addColumn('action', function($row){
                            $action = '';
                            if(Gate::allows('download file')){
                                $action .= '<a href="' . route("payments.download", Crypt::encrypt($row->id)) . '" target="_blank" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Unduh File">
                                    <i class="bi bi-download"></i>
                                </a>';
                            }
                            if(Gate::allows('delete file')){
                            $action .= '<form method="post" action="' . route("payments.destroy", Crypt::encrypt($row->id)) . '"
                                id="deletePayment" style="display:inline" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Hapus Data">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>';
                            }
                            return $action;
                        })
                        ->rawColumns(['nik_fullname','ttl_jk','pay','action'])->make(true);
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
                'date' => 'required|date',
                'pay' => 'required|string|max:191',
                'file' => 'max:2048|mimes:png,jpg,jpeg,pdf',
            ]);

            if($request->file('file')){
                $file = $request->file('file')->store('public/payment');
            }else{
                $file = null;
            }

            $cek = Payment::where('jamaah_id',$request->jamaah_id)->orderBy('id','desc')->first();
            if($cek){
                $pay = Helper::delMask('.',$request->pay);
                $amount = (int) $pay + (int) $cek->amount;
            }else{
                $amount = Helper::delMask('.',$request->pay);
            }

            Payment::create([
                'jamaah_id' => $request->jamaah_id,
                'date' => $request->date,
                'pay' => Helper::delMask('.',$request->pay),
                'amount' => $amount,
                'file' => $file,
                'description' => ucwords($request->description),
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
        $data = Payment::find($id);
        if($data->file!=null){
            File::delete('storage/payment/'.Helper::getFilename($data->file));
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
        $data = Payment::with('jamaahs')->find($id);
        if($data->file==null){
            $path = 'no-image.jpeg';
        }else{
            $path = '/storage/payment/'.Helper::getFilename($data->file);
        }
        $ext = pathinfo($path,PATHINFO_EXTENSION);
        $filename = $data->jamaahs->fullname.'_'.$data->date.'.'.$ext;
        return response()->download(public_path($path),$filename);
    }
}