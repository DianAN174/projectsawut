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


Class DaftarPengguna
{
    protected $admin;

    public function __construct(User $user)
    {
        $this->admin = $user;
    }

    public function Create(Request $request)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_pengguna' => 'required',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'peran' => 'required|in:akuntan,nazhir,bendahara',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            $request['password'] = Hash::make($request['password']);

            DB::beginTransaction();

            $user = new User();

            $user->name = $request->nama_pengguna;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->created_by = $this->admin->name;
            $user->modified_by = $this->admin->name;

            $newUser = $user->save();

            if (!$newUser) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $userRole = $request->peran;
            switch ($userRole) {
                case "akuntan":
                    $user->role_id = '2';
                    $newRole = $user->save();

                    if (!$newRole) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                case "nazhir":
                    $user->role_id = '3';
                    $newRole = $user->save();

                    if (!$newRole) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                case "bendahara":
                    $user->role_id = '4';
                    $newRole = $user->save();

                    if (!$newRole) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newUser, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Index(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'page' => 'numeric',
                'limit' => 'numeric',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            $results = User::join("roles","users.role_id","=","roles.id")
            ->select(DB::raw("users.*"), "roles.name as nama_peran")                        
            ->get();

            //$results = User::select('name','email','password')->roles->name;
            

            return Response::HttpResponse(200, $results, "Index", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Edit(Request $request, $id)
    {
        try 
        {
            $user = User::join("roles","users.role_id","=","roles.id")
            ->select(DB::raw("users.name, users.email"), "roles.name as nama_peran")      
            ->where('users.id',$id)                  
            ->get();
            
            return Response::HttpResponse(200, $user, "Info User yang akan diedit berhasil ditampilkan", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Update(Request $request,$id)
    {

        try {

            $this->admin = $request->user();

            $validator = Validator::make($request->all(), [
                'nama_pengguna' => 'required',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'peran' => 'required|in:akuntan,nazhir,bendahara',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()->all()];
                return Response::HttpResponse(422, $response, "Invalid Data", false);
            }

            DB::beginTransaction();

            $user = User::find($id);
            
            $request['password'] = Hash::make($request['password']);

            $user->name = $request->nama_pengguna;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->created_by = $this->admin->name;
            $user->modified_by = $this->admin->name;

            $newUser = $user->save();

            if (!$newUser) {
                DB::rollBack();
                return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            $userRole = $request->peran;
            switch ($userRole) {
                case "akuntan":
                    $user->role_id = '2';
                    $newRole = $user->save();

                    if (!$newRole) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                case "nazhir":
                    $user->role_id = '3';
                    $newRole = $user->save();

                    if (!$newRole) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                case "bendahara":
                    $user->role_id = '4';
                    $newRole = $user->save();

                    if (!$newRole) {
                        DB::rollBack();
                        return Response::HttpResponse(400, null, "Failed to create data wakif", true);
                    }
                    break;
                default:
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data wakif", true);
            }

            DB::commit();

            return Response::HttpResponse(200, $newUser, "Success", false);
        } catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function Delete(Request $request,int $id) {
        try {
            $currData = User::find($id);

            $this->admin = $request->user();

            $currData->deleted_by = $this->admin->name;

            $currData->save();
            
            $currData->delete();


            return Response::HttpResponse(200, null, "Success", true);
        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }

    public function DropdownPeran(Request $request){
        $peran=['akuntan'=>'Akuntan','nazhir'=>'Nazhir','bendahara'=>'Bendahara'];

        return Response::HttpResponse(200, $peran, "Success", true);
    }

    public function Search(Request $request) {

        $inputs =  $request->all();
        $validator = Validator::make($inputs, [
            'search_type' => 'min:1',
            'value' => 'min:1',
        ]);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()->all()];
            return Response::HttpResponse(422, $response, "Invalid Data", false);
        }

        $datas = User::where($inputs["search_type"],"like","%".$inputs["value"]."%")->paginate(10);
        return Response::HttpResponse(200, $datas, "OK", false);
    }

    public function Status(Request $request, $id)
    {
        try
        {
            $this->admin = $request->user();
            $user = User::find($id);

            $status = $user->status;
            
            DB::beginTransaction();

            if($status=='checking')
            {
                $user->status = 'terdaftar';
                
                /* $user->approved_at = \Carbon\Carbon::now();
                $user->approved_by = $this->admin->name; */
                
            }

            $newUser = $user->save();

                if (!$newUser) {
                    DB::rollBack();
                    return Response::HttpResponse(400, null, "Failed to create data ", true);
                }

            DB::commit();

            return Response::HttpResponse(200, $newUser, "Success", false);

        }catch (Exception $e) {
            return Response::HttpResponse(500, ['errors' => $e->getTraceAsString()], "Internal Server Errorr", true);
        }
    }
}