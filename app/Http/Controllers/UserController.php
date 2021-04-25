<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class UserController extends Controller
{
    
    //menampilkan data-data User dan mengurutkan berdasar waktu dibuat
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(10);
        //return view('users.index', compact('users'));
        $response = [
            "success" => true,
            "message" => "Users retrieved successfully.",
            "data" => $user
            ];
        return response($response, 200);
    }

    //mengambil data-data Role dan mengurutkan berdasar nama, ascending
    public function create()
    {
        $role = Role::orderBy('name', 'ASC')->get();
        //return view('users.create', compact('role'));
        $response = [
            "success" => true,
            "message" => "Roles retrieved successfully.",
            "data" => $role
            ];
        return response($response, 200);
    }

    //fungsi tambahkan user dan rolenya
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::firstOrCreate([
            'email' => $request->email
        ], [
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'status' => true
        ]);

        $user->assignRole($request->role);
        $response = [
            "success" => true,
            "message" => "Users added successfully.",
            "data" => $user
            ];
        return response($response, 200);
        //return redirect(route('users.index'))->with(['success' => 'User: <strong>' . $user->name . '</strong> Ditambahkan']);
    }

    //edit profil user -> nampilin data user saat ini yang akan diedit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $response = [
            "success" => true,
            "message" => "User retrieved successfully.",
            "data" => $user
            ];
        return response($response, 200);

/*         return response()->json([
            "success" => true,
            "message" => "User retrieved successfully.",
            "data" => $user
            ]); */
    }

    //update profil user
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::findOrFail($id);
        $password = !empty($request->password) ? bcrypt($request->password):$user->password;
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password
        ]);

        $response = [
            "success" => true,
            "message" => "User updated successfully.",
            "data" => $user
            ];
        return response($response, 200);
    }

    
    /* public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with(['success' => 'User: <strong>' . $user->name . '</strong> Dihapus']);
    } */

    public function rolePermission(Request $request)
    {
        $role = $request->get('role');

        //Default, set dua buah variable dengan nilai null
        $permissions = null;
        $hasPermission = null;

        //Mengambil data role
        $roles = Role::all()->pluck('name');

        //apabila parameter role terpenuhi
        if (!empty($role)) {
            //select role berdasarkan namenya, ini sejenis dengan method find()
            $getRole = Role::findByName($role);

            //Query untuk mengambil permission yang telah dimiliki oleh role terkait
            $hasPermission = DB::table('role_has_permissions')
                ->select('permissions.name')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->where('role_id', $getRole->id)->get()->pluck('name')->all();

            //Mengambil data permission
            $permissions = Permission::all()->pluck('name');
        }
        //return view('users.role_permission', compact('roles', 'permissions', 'hasPermission'));
        $response = [
            "success" => true,
            "message" => "Permission retrieved successfully.",
            "data" => $roles
            ];
        return response($response, 200);
    }

    //nambahkan permission
    /* public function addPermission(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:permissions'
        ]);

        $permission = Permission::firstOrCreate([
            'name' => $request->name
        ]);
        return redirect()->back();
    }

    //assign permission ke role
    public function setRolePermission(Request $request, $role)
    {
        //select role berdasarkan namanya
        $role = Role::findByName($role);

        //fungsi syncPermission akan menghapus semua permissio yg dimiliki role tersebut
        //kemudian di-assign kembali sehingga tidak terjadi duplicate data
        $role->syncPermissions($request->permission);
        return redirect()->back()->with(['success' => 'Permission to Role Saved!']);
    } */

    //mengambil data roles
    public function roles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all()->pluck('name');
        //return view('users.roles', compact('user', 'roles'));
        $response = [
            "success" => true,
            "message" => "Role retrieved successfully.",
            "data" => $roles
            ];
        return response($response, 200);
    }

    //set role kepada user
    public function setRole(Request $request, $id)
    {
        $this->validate($request, [
            'role' => 'required'
        ]);
        
        $user = User::findOrFail($id);
        //menggunakan syncRoles agar terlebih dahulu menghapus semua role yang dimiliki
        //kemudian di-set kembali agar tidak terjadi duplicate
        $user->syncRoles($request->role);
        //return redirect()->back()->with(['success' => 'Role Sudah Di Set']);
        $response = [
            "success" => true,
            "message" => "Role set successfully.",
            "data" => $user
            ];
        return response($response, 200);
    }
}

