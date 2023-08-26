<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('backapp.settings.user.index');
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
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required'
            ]);

            $insert = User::create([
                'name' => ucwords($request->name),
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'type' => $request->role
            ]);

            $insert->assignRole($request->role);
            // $insert->sendEmailVerificationNotification();

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
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        return view('backapp.settings.user.profile',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::with(['roles'])->find($id);
        $linkUpdate = route("users.update", Crypt::encrypt($data->id));
        return response()->json(['data' => $data, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:users,email,' . $id,
                'role' => 'required'
            ]);

            $db = User::find($id);

            $db->name = ucwords($request->name);
            $db->email = $request->email;
            $db->type = $request->role;

            $db->update();
            $db->syncRoles($request->role);

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
        $data = User::where('id', $id);
        // $isExist = User::where('unit_kerja_id', $id)->first();
        // if ($isExist) {
        //     return false;
        // } else {
        //     $data->delete();
        //     return true;
        // }
        $data->delete();
        return true;
    }

    public function password_update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/'
            ]);

            $db = User::find($id);
            $db->password = Hash::make($request->password);

            $db->update();

            return response()->json([
                'status' => true, 'message' => 'Berhasil merubah password'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function profile_update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:users,email,' . $id,
            ]);

            $db = User::find($id);
            $db->name = ucwords($request->name);
            $db->email = $request->email;

            $db->update();

            return response()->json([
                'status' => true, 'message' => 'Berhasil update profil'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        // return redirect('admin/users')->withToastSuccess('Berhasil mengubah data');
    }
}