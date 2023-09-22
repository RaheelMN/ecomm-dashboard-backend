<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isEmpty;

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
        if(!$user || !Hash::check($req->password,$user->password)){
            return ['error'=>'name or password not matched'];
        }else
        return $user;
        // $input = $req->input();
        // $result=db::select('SELECT * FROM users where `name`=?',[$input['name']]);
        // if($result){
        //     return response(json_encode($result),200)
        //     ->header('Content-Type','application/json')
        //     ->header('Access-Control-Allow-Origin','*');        
        // }
        // else{
        //     return response(json_encode(['message'=>'result not found']),200)
        //     ->header('Content-Type','application/json')
        //     ->header('Access-Control-Allow-Origin','*');                         
        // }
    }

    public function register(Request $req){

        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->save();

        return response(json_encode($user))
        ->header('Content-Type','application/json')
        ->header('Access-Control-Allow-Origin','*');


        // return $user;
        // $data =['name'=>$req->name,'email'=>$req->email,'password'=>$req->password ];
        // $result = db::table('users')->insertOrIgnore($data);
        // if($result){
        //     echo "data inserted sussfully";
        // }else{
        //     echo "email already exists"; 
        // }
 
        // $data =['name'=>$req->name,'age'=>$req->age,'email'=>$req->email,'city'=>$req->city ];
        // $result=db::table('students')->insertOrIgnore($data);

        // $result = db::insert('INSERT INTO students (`name`,`age`,`email`,`city`) VALUES (?,?,?,?)',[$req->name,$req->age,$req->email,$req->city]);
        // if($result==1){
        //     return redirect()->route('showStudents');
        // }else{  

        // $validate=$req->validate([
        //     'name'=>'required|alpha|max:50',
        //     'email'=>'required|email|max:255',
        //     'password'=>'required|between:6,6'
        // ],['name.required'=>'Enter name',
        //   'email.required'=>'enter email',
        //   'password.required'=>'enter password']);

    }

        // public function addStudent(Request $req){
        // $req->validate([
        //     'name'=>'required|alpha|max:20',
        //     'email'=>'email|max:255|unique:App\Models\student,email',
        //     'age'=>'numeric|between:18,24',
        //     'city'=>'numeric|min:1'
        // ],['name.required'=>'Please enter the name',
        //     'name.alpha'=>'Name should consist of letters',
        //     'city.min'=>'Select one of the city in dropdown menu']);
        // // $data =['name'=>$req->name,'age'=>$req->age,'email'=>$req->email,'city'=>$req->city ];
        // // $result=db::table('students')->insertOrIgnore($data);

        // $result = db::insert('INSERT INTO students (`name`,`age`,`email`,`city`) VALUES (?,?,?,?)',[$req->name,$req->age,$req->email,$req->city]);
        // if($result==1){
        //     return redirect()->route('showStudents');
        // }else{
        //     echo 'Email already exists';
        // }
}





