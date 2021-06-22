<?php

namespace App\Http\Controllers\Wakaf;

use App\Models\User;
use App\Models\Role;
use App\Utils\Response;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Mockery\Exception;


Class DataAkun
{
    protected $admin;

    public function __construct(User $user)
    {
        $this->admin = $user;
    }

    //halaman Data Akun

    public function Index(Request $request)
    {
        try 
        {
            $dataUser = $request->user();
            /* $user = User::join("roles","users.role_id","=","roles.id")
            ->select(DB::raw("users.name, users.email, users.password"), "roles.name as nama_peran")  
            ->where("users.id",$id)            
            ->get();*/
            return Response::HttpResponse(200, $dataUser, "Info User yang akan diedit berhasil ditampilkan", false); 
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function uploadAvatar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()->all()];
            return Response::HttpResponse(422, $response, "Invalid Data", false);
        }

        $filename = $request->image->getClientOriginalName();
        $request->image->storeAs('images',$filename,'public');
        
        DB::beginTransaction();

        $user = User::find($id);

        $user->avatar = $filename;
        $user->modified_by = $this->admin->name;

        $newUser = $user->save();

        if (!$newUser) {
            DB::rollBack();
            return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newUser, "Success", false);

    }
    
    public function EditProfil(Request $request)
    {
        try 
        {
            /* $user = User::join("roles","users.role_id","=","roles.id")
            ->select(DB::raw("users.name, users.email, users.password"), "roles.name as nama_peran")      
            ->where('users.id',$id)                  
            ->get(); */
            $dataUser = $request->user();
            return Response::HttpResponse(200, $user, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Update(Request $request)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_pengguna' => 'required',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            //$user = User::find($id);
            $user = $request->user()->id;
            $request['password'] = Hash::make($request['password']);
            $user->nama_pengguna = $request->nama_pengguna;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->created_by = $this->admin->name;
            $user->modified_by = $this->admin->name;

            $newUser = $user->save();

            if (!$newUser) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newUser, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }


}