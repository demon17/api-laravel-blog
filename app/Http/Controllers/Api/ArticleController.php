<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Validator;
use Response;
use App\Http\Resources\ArticleResource;
use Illuminate\Validation\Rule;
use App\Models\Article;
use Auth;
use Illuminate\Support\Str;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Api
 */
class ArticleController extends Controller {
    /**
     * show all the article
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(){
        return ArticleResource::collection(Article::where('author_id',Auth::user()->id)->orderBy('created_at','DESC')->paginate(10));
    }

    /**
     * check title validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkTitle(Request $request){
        $validators = Validator::make($request->all(),[
            'title'=>'required'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * check category validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkCategory(Request $request){
        $validators = Validator::make($request->all(),[
            'category'=>'required'
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
            'description'=>'required'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * store new article into the database
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        $validators=Validator::make($request->all(),[
            'title'=>'required',
            'category'=>'required',
            'description'=>'required',
            'vote'=>'required'
            // TODO: add questions one to many
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $article=new Article();
            $article->title=$request->title;
            $article->author_id=Auth::user()->id;
            $article->category_id=$request->category;
            $article->description=$request->description;
            $article->vote=$request->vote;
            $article->created_at = Carbon::now();
            // TODO: add questions one to many
            $article->save();
            return Response::json(['success'=>'Article created successfully !']);
        }
    }

    /**
     * show a specific article by id
     *
     * @param $id
     * @return ArticleResource
     */
    public function show($id){
        if(Article::where('id',$id)->first()){
            return new ArticleResource(Article::findOrFail($id));
        }else{
            return Response::json(['error'=>'Article not found!']);
        }
    }

    /**
     * update article using id
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request){
        $validators=Validator::make($request->all(),[
            'title'=>'required',
            'category'=>'required',
            'description'=>'required',
            'vote'=>'required'
            // TODO: add questions one to many
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $article=Article::where('id',$request->id)->where('author_id',Auth::user()->id)->first();
            if($article){
                $article->title=$request->title;
                $article->author_id=Auth::user()->id;
                $article->category_id=$request->category;
                $article->description=$request->description;
                $article->vote=$request->vote;
                // TODO: add questions one to many
                $article->save();
                return Response::json(['success'=>'Article updated successfully !']);
            }else{
                return Response::json(['error'=>'Article not found !']);
            }
        }
    }

    /**
     * remove article using id
     *
     * @param Request $request
     * @return mixed
     */
    public function remove(Request $request){
        try{
            $article=Article::where('id',$request->id)->where('author_id',Auth::user()->id)->first();
            if($article){
                $article->delete();
                return Response::json(['success'=>'Article removed successfully !']);
            }else{
                return Response::json(['error'=>'Article not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'You cann\'t delete this article!']);
        }
    }

    /**
     * search article by keyword
     *
     * @param Request $request
     * @return mixed
     */
    public function searchArticle(Request $request){
        $results = Article::query();
        $results->join("categories", "categories.id", "=", "articles.category_id");
        $results->when($request->id, function ($q) use ($request) {
            $q->where('articles.category_id', $request->id);
        });
        $results->when($request->keyword, function ($q) use ($request) {
            $q->where('title', 'LIKE', '%' . $request->keyword .'%')
                ->orWhere('description', 'LIKE', '%' . $request->keyword . '%');
        });
        $results->orderBy('articles.created_at','DESC');
        $articles=$results->paginate(10);

        return ArticleResource::collection($articles);
    }
}
