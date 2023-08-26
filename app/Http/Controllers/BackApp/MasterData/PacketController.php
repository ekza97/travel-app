<?php

namespace App\Http\Controllers\BackApp\MasterData;

use Exception;
use App\Models\Packet;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\DataTables\PacketDataTable;
use App\Http\Controllers\Controller;
use App\Models\Airplane;
use App\Models\Category;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

class PacketController extends Controller
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
    public function index(PacketDataTable $dataTable)
    {
        $masterData = true;
        return $dataTable->render('backapp.masterData.packet.index',compact('masterData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterData = true;
        $category = Category::all();
        $airplane = Airplane::all();
        $hotel_mekkah = Hotel::where('type','Mekkah')->get();
        $hotel_madinah = Hotel::where('type','Madinah')->get();
        return view('backapp.masterData.packet.create',compact('masterData','category','airplane','hotel_mekkah','hotel_madinah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:191',
                'cost' => 'required|string|max:191',
                'discount' => 'required|string|max:191',
                'image' => 'max:2048|mimes:png,jpg,jpeg',
            ]);

            if($request->file('image')){
                $image = $request->file('image')->store('public/packet');
            }else{
                $image = null;
            }

            Packet::create([
                'category_id' => $request->category_id,
                'airplane_id' => $request->airplane_id,
                'mekkah_hotel_id' => $request->mekkah_hotel_id,
                'madinah_hotel_id' => $request->madinah_hotel_id,
                'title' => ucwords($request->title),
                'cost' => Helper::delMask('.',$request->cost),
                'discount' => $request->discount,
                'description' => $request->description,
                'image' => $image,
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
        $edit = Packet::find($id);
        $masterData = true;
        $category = Category::all();
        $airplane = Airplane::all();
        $hotel_mekkah = Hotel::where('type','Mekkah')->get();
        $hotel_madinah = Hotel::where('type','Madinah')->get();
        return view('backapp.masterData.packet.edit',compact('masterData','edit','category','airplane','hotel_mekkah','hotel_madinah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'title' => 'required|string|max:191',
                'cost' => 'required|string|max:191',
                'discount' => 'required|string|max:191',
                'image' => 'max:2048|mimes:png,jpg,jpeg',
            ]);

            $db = Packet::find($id);

            if($request->file('image')){
                if($db->image!=null){
                    File::delete('storage/packet/'.Helper::getFilename($db->image));
                }
                $image = $request->file('image')->store('public/packet');
            }else{
                $image = null;
            }

            $db->category_id = $request->category_id;
            $db->airplane_id = $request->airplane_id;
            $db->mekkah_hotel_id = $request->mekkah_hotel_id;
            $db->madinah_hotel_id = $request->madinah_hotel_id;
            $db->title = ucwords($request->title);
            $db->cost = Helper::delMask('.',$request->cost);
            $db->discount = $request->discount;
            $db->description = $request->description;
            $db->image = $image;
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
        $data = Packet::find($id);
        if($data->image!=null){
            File::delete('storage/packet/'.Helper::getFilename($data->image));
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