<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Response;
use App\Http\Resources\CategoryResource;
use Illuminate\Validation\Rule;
use App\Models\Category;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Api
 */
class CategoryController extends Controller {

    /**
     * show all the categories
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(){
        return CategoryResource::collection(Category::orderBy('id','DESC')->paginate(10));
    }

    /**
     * top categories
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function topCategories(){
        $results = Category::query();
        $results->select([
            'categories.title',
            DB::raw('COUNT(articles.id) as total'),
            DB::raw('SUM(articles.vote) as votes')
        ]);
        $results->leftJoin("articles", "articles.category_id", "=", "categories.id");
        $results->limit(5);
        $results->groupBy('categories.title');
        $results->orderBy('votes', 'DESC');
        $results->havingRaw('COUNT(articles.id) > 1');
        $categories=$results->get();

        if(count($categories)==0){
            return Response::json(['message'=>'No categories match found !']);
        }else{
            return Response::json($categories);
        }
    }

    /**
     * check title validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkTitle(Request $request){
        $validators = Validator::make($request->all(),[
            'title'=>'required|unique:categories',
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * check description validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkDescription(Request $request){
        $validators = Validator::make($request->all(),[
            'description'=>'required|unique:categories'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * store new category into the database
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        $validators=Validator::make($request->all(),[
            'title'=>'required|unique:categories',
            'description'=>'required|unique:categories'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $category=new Category();
            $category->title=$request->title;
            $category->description=strtolower(implode('-',explode(' ',$request->description)));
            $category->save();
            return Response::json(['success'=>'Category created successfully !']);
        }
    }

    /**
     * show a specific category by id
     *
     * @param $id
     * @return CategoryResource
     */
    public function show($id){
        if(Category::where('id',$id)->first()){
            return new CategoryResource(Category::findOrFail($id));
        }else{
            return Response::json(['error'=>'Category not found!']);
        }
    }

    /**
     * check edit title validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkEditTitle(Request $request){
        $validators = Validator::make($request->all(),[
            'title'=>['required',Rule::unique('categories')->ignore($request->id)]
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * check edit description validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkEditDescription(Request $request){
        $validators = Validator::make($request->all(),[
            'description'=>['required',Rule::unique('categories')->ignore($request->id)]
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * update category using id
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request){
        $validators=Validator::make($request->all(),[
            'title'=>['required',Rule::unique('categories')->ignore($request->id)],
            'description'=>['required',Rule::unique('categories')->ignore($request->id)]
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $category=Category::findOrFail($request->id);
            $category->title=$request->title;
            $category->description=strtolower(implode('-',explode(' ',$request->description)));
            $category->save();
            return Response::json(['success'=>'Category updated successfully !']);
        }
    }

    /**
     * remove category using id
     *
     * @param Request $request
     * @return mixed
     */
    public function remove(Request $request){
        try{
            $category=Category::where('id',$request->id)->first();
            if($category){
                $category->delete();
                return Response::json(['success'=>'Category removed successfully !']);
            }else{
                return Response::json(['error'=>'Category not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Category belongs to an article.So you cann\'t delete this category!']);
        }
    }

    /**
     * search category by keyword
     *
     * @param Request $request
     * @return mixed
     */
    public function searchCategory(Request $request){
        $categories=Category::where('title','LIKE','%'.$request->keyword.'%')->orWhere('description','LIKE','%'.$request->keyword.'%')->get();
        if(count($categories)==0){
            return Response::json(['message'=>'No category match found !']);
        }else{
            return Response::json($categories);
        }
    }

}
