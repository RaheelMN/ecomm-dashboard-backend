<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as VValidator;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller

{
    private function validatefunc(Request $req) : VValidator {
        $rules=array(
            'image' => 'image|mimes:jpeg,jpg,png,gif|max:15',
            'name'=>'regex:/^[\pL\s\.]+$/u|max:50',
            'description'=>'regex:/^[a-zA-Z]+[ ,.%+\-\(\)\'&a-zA-Z0-9]*$/u|max:250',
            'price'=>'numeric|between:0,99999999.99',
        );
        $messages=array(
          'image.image'=>'File is not image',
          'image.mimes'=>'Image should be of jpeg,jpg,png,gif type',
          'image.max'=>'Image size should be less then 15kb',
          'name.regex'=>'Name should be alphabets',
          'description.regex'=>'Description should not include special characters',

        );
        $validator=Validator::make($req->all(),$rules,$messages);
        return $validator;
    }
    public function addProduct(Request $req){
        $output=[];
        $output['error']=false;

        $validator = $this->validatefunc($req);     
        if($validator->fails())
        {
            $output['error']=true;
            $output['data']=$validator->messages();
            return $output;
        }else{

            $product = new Product;
            $product->name = $req->name;
            $product->file_path =  $req->file('image')->store('images');
            $product->description = $req->description;
            $product->price = $req->price;
            $result=$product->save();
            if($result){
                return ['message'=>"Product successfully added"];
            }else{
                return ['message'=>"Failed to add product"];
            }
        }        
    }

    public function updateProduct(Request $req){

        $output=[];
        $output['error']=false;

        $validator = $this->validatefunc($req);
        if($validator->fails())
        {
            $output['error']=true;
            $output['data']=$validator->messages();
            return $output;
        }else{
            $product = Product::find($req->id);
            if($req->image !=''){
                Storage::delete('/'.$product->file_path);
                $product->file_path=$req->file('image')->store('images');
            }           
            $product->description = $req->description;
            $product->price = $req->price;
            $result=$product->save();
            if($result){
                return ['message'=>"Product successful updated"];
            }else{
                return ['message'=>"Failed to add product"];
            }
        }
    }

    public function viewProducts(){
        return Product::all();
    }

    public function deleteProduct(int $id){
        $result = Product::where('id',$id)->delete();
        if($result){
            return ['message'=>"Product successfully deleted"];
        }else{
            return ['message'=>"Product doesnot exists"];
        }
    }

    public function getProduct(int $id){
        $result = Product::find($id);
        if($result){
            return $result;
        }else{
            return ['message'=>"Product doesnot exits"];
        }
    }

    public function searchProduct($key){
        $result = Product::select('id','name','file_path','description','price')->where('name','like',"%{$key}%")->get();
        return $result;
        // $result = Product::find($id);
        // if($result){
        //     return $result;
        // }else{
        //     return ['message'=>"Product doesnot exits"];
        // }
    }    
}
