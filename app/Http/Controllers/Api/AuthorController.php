<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorResource;
use App\Models\User;
use Response;
use Validator;
use Illuminate\Support\Str;
use Auth;

/**
 * Class AuthorController
 * @package App\Http\Controllers\Api
 */
class AuthorController extends Controller {

    /**
     * show all the users
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(){
        return AuthorResource::collection(User::orderBy('id','DESC')->paginate(10));
    }

    /**
     * check name validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkName(Request $request){
        $validators=Validator::make($request->all(),[
            'name'=>'required'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * check email validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkEmail(Request $request){
        $validators=Validator::make($request->all(),[
            'email'=>'required|email|unique:users'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * check password validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkPassword(Request $request){
        $validators=Validator::make($request->all(),[
            'password'=>'required'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * register user
     *
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request){
        $validators=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $author=new User();
            $author->name=$request->name;
            $author->email=$request->email;
            $author->password=bcrypt($request->password);
            $author->api_token=Str::random(80);
            $author->save();
            return Response::json(['success'=>'Registration done successfully !','author'=>$author]);
        }
    }

    /**
     * login user
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request){
        $validators=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
                $author=$request->user();
                $author->api_token=Str::random(80);
                $author->save();
                return Response::json(['loggedin'=>true,'success'=>'Login was successfully !','author'=>Auth::user()]);
            }else{
                return Response::json(['loggedin'=>false,'errors'=>'Login failed ! Wrong credentials.']);
            }
        }
    }

    /**
     * get authenticated author
     *
     * @return mixed
     */
    public function getAuthor(){
        $author = [];
        $author['name'] = Auth::user()->name;
        $author['email'] = Auth::user()->email;
        return Response::json($author);
    }

    /**
     * log the author out
     *
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request){
        $author=$request->user();
        $author->api_token=NULL;
        $author->save();
        return Response::json(['message'=>'Logged out!']);
    }
}
