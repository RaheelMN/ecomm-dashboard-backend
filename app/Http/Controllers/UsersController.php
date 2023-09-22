<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    //
    public function getUsers(){
        $records = DB::select('SELECT * FROM users');
        return response(json_encode($records),200)
        ->header('Content-Type','application/json')
        ->header('Access-Control-Allow-Origin','*');
    }

    public function login(Request $req){
        $user = User::where('email',$req->email)->first();
        $output = [];
        $output['error']=false;
        if(!$user){
            $output['error']=true;
            $output['email_err']="Email not found";
            return $output;
        }elseif(!Hash::check($req->password,$user->password)){
            $output['error']=true;
            $output['pass_err']="password not match";
            return $output;
        }else{
            $output['data']=$user;
            return $output;
        }
    }

    public function register(Request $req){
        $output=[];
        $output['error']=false;
        $user = new User;
        
        $rules=array(
            'name'=>'required|regex:/^[\pL\s\.]+$/u|max:50',
            'email'=>'required|email|unique:App\Models\User,email|max:255',
            'password'=>'required|between:6,6'
        );
        $messages=array(
          'name.required'=>'Enter name',
          'name.regex'=>'Name should be alphabets',
          'name.max'=>'Name should be less then 50 characters',
          'email.required'=>'enter email',
          'email.email'=>'Email format not valid',
          'email.unique'=>'Email already exists',
          'email.max'=>'Email should be less then 255 characters',
          'password.required'=>'enter password',
          'password.between'=>'Password should be six characters',
        );

        $validator=Validator::make($req->all(),$rules,$messages);
        if($validator->fails())
        {
            $output['error']=true;
            $output['data']=$validator->messages();
            return $output;
        }else{

            $user->name= $req->name;
            $user->email= $req->email;
            $user->password= $req->password;
            $result = $user->save();
            if($result){

                $output['data']=$user;
                $output['data']['id']=$user->id;
                return $output;
            }
        }
    }
}





