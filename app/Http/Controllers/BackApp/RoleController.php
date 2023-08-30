<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class RoleController extends Controller
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
    public function index(RoleDataTable $dataTable)
    {
        $modules = [
            'all_permission'        => 'Administrator Permissions',
            // 'kontak_kami'          => 'Kontak Kami',
            // 'dashboard'          => 'Dashboard Management',
            'category'           => 'Modul Kategori',
            'document'           => 'Modul Dokumen',
            'airplane'           => 'Modul Pesawat',
            'hotel'           => 'Modul Hotel',
            'agent'           => 'Modul Agent',
            'packet'           => 'Modul Paket',
            'schedule'           => 'Modul Jadwal',
            'form registration'           => 'Modul Formulir Pendaftaran',
            'jamaah'           => 'Modul Jamaah',
            'file'           => 'Modul Dokumen Jamaah',
            'payment'           => 'Modul Pembayaran Jamaah',
            'cover-letter'           => 'Modul Surat Pengantar',
            'report'           => 'Modul Laporan',
            'permission'            => 'Modul Permission',
            'role'                  => 'Modul Role',
            'user'                  => 'Modul Pengguna',
        ];
        $settings = true;
        return $dataTable->render('backapp.settings.role.index',compact('modules','settings'));
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
                'name' => 'required|alpha_dash|min:3|unique:roles,name',
                'permission' => 'required|array'
            ]);

            $insert = Role::create([
                'name' => $request->name,
            ]);
            $insert->givePermissionTo($request->permission);

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
        $data = Role::with('permissions')->find($id);
        $linkUpdate = route("roles.update", Crypt::encrypt($data->id));
        return response()->json(['name' => $data->name, 'permission'=>$data->permissions, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|alpha_dash|min:3|unique:roles,name,' . $id,
                'permission' => 'required|array'
            ]);

            $db = Role::find($id);

            $db->name = $request->name;

            $db->update();
            $db->syncPermissions($request->permission);

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
        $data = Role::where('id', $id);
        $data->delete();
        return true;
    }
}