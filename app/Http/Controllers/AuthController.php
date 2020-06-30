<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;
use App\Http\Helper\ResponseBuilder;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users|max:255',
            'password' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users|max:255',
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::result(422,false,"Failed",$validator->errors());
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->username = $request->username;
        $user->save();
        $status=true;
        $info="user added successfully";
        $result=$user;
        return ResponseBuilder::result(200,$status,$info,$result);
    }

    public function currentUser()
    {
        $token = $this->jwt->getToken();
        $this->jwt->user();
        $data = $this->jwt->setToken($token)->toUser();
        $status=true;
        $info="user found";
        $result=$data;
        return ResponseBuilder::result(200,$status,$info,$result);
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'    => 'required|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::result(422,false,"Failed",$validator->errors());
        }
        
        try {
            if (! $token = $this->jwt->attempt($request->only('username', 'password'))) {
                $code=404;
                $status=false;
                $info="user not found";
                $result='';
                return ResponseBuilder::result($code,$status,$info,$result);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $code=500;
            $status=false;
            $info="token expired";
            $result='';
            return ResponseBuilder::result($code,$status,$info,$result);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $code=500;
            $status=false;
            $info="token invalid";
            $result='';                
            return ResponseBuilder::result($code,$status,$info,$result);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $code=500;
            $status=false;
            $info="token_absent";
            $result=$e->getMessage();    
            return ResponseBuilder::result($code,$status,$info,$result);
        }
        return ResponseBuilder::result(200,true,'Login successfully',['token'=>$token,'token_type'=>'Bearer']);
    }

    public function showAllUsers()
    {
        $code=200;
        $status=true;
        $info='User List';
        $result=User::all();
        return ResponseBuilder::result($code,$status,$info,$result);
    }

    public function showOneUsers($id)
    {
        return ResponseBuilder::result(200,true,'Data fatched successfuly',User::find($id));
    }

    public function update($id, Request $request)
    {
        if (User::where('id', $id)->exists()) {
            $User = User::find($id);
            $User->name = is_null($request->name) ? $User->name : $request->name;
            $User->save();    
            return ResponseBuilder::result(200,true,'Data updated successfuly',$User);
        } else {
            return ResponseBuilder::result(404,false,'data not found');
        }
    }

    public function delete($id)
    {
        if (User::where('id', $id)->exists()) {
            User::where('id', $id)->delete();
            return ResponseBuilder::result(200,true,'Data updated successfuly');
        } else {
            return ResponseBuilder::result(404,false,'data not found');
        }
    }
}