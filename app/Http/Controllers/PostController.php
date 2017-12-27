<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;//Use SQL Queies Instead ORM

class PostController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=>['index','show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()//SELECT * FROM Posts
    {
        //ORM-Object Relational Mapper
        //$posts = Post::all();

        $posts = Post::orderBy('created_at','desc')->paginate(10);

        //$posts = Post::where('title','Post Two')->get();

        //DB-SQL
        //$posts = DB::select('SELECT * FROM Posts limit 1');
        return view('posts.index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$post=DB::insert('INSERT INTO Post VALUES()');
        return view('Posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'body' =>'required',
            'cover_image'=>'image|nullable|max:1999'//MAximum file size 2MB
        ]);
        
        //Handle File Upload
        if($request->hasFile('cover_image')){
            
            //Get File NAme with Ext
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            //Get File Name
            $fileName = pathinfo($fileNameWithExt,PATHINFO_FILENAME);

            //Get Ext
            $fileExt = $request->file('cover_image')->getClientOriginalExtension();

            //FileName to store
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;

            //Upload Image
            $path = $request->file('cover_image')->storeAs('public/coverImages',$fileNameToStore);


        } else{
            $fileNameToStore = 'noimage.jpg';
        }

        //Create New Post
        $post = new Post;//Because WE have imported App\Post
        //Add fields
        $post->title = $request->input('title') ;
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success','Post Created');//Message with Success
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)//SELECT * FROM Posts Where id={id}
    {    
        $post = Post::find($id);        
        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Check for correct user
        
        $post = Post::find($id);

        //Check for correct user
         if(auth()->user()->id !== $post->user_id){
             return redirect('/posts')->with('error',' Unauthorized Page ');    
         }
        return view('posts.edit')->with('post',$post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title'=>'required',
            'body' =>'required',
            'cover_image'=>'image|nullable|max:1999'
        ]);
        
        //Handle File Upload
        if($request->hasFile('cover_image')){
            
            //Get File NAme with Ext
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            //Get File Name
            $fileName = pathinfo($fileNameWithExt,PATHINFO_FILENAME);

            //Get Ext
            $fileExt = $request->file('cover_image')->getClientOriginalExtension();

            //FileName to store
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;

            //Upload Image
            $path = $request->file('cover_image')->storeAs('public/coverImages',$fileNameToStore);
        }       



        //Create New Post
        $post = Post::find($id);//Because WE have imported App\Post
        //Add fields
        $post->title = $request->input('title') ;
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore; 
        }
        
        $post->save();

        return redirect('/posts')->with('success','Post Updated');//Message with Success
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $post=Post::find($id);

       if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error',' Unauthorized Page ');    
       }

       if($post->cover_image != 'noimage.jpg'){
            Storage::delete('/storage/coverImages/.{{$post->cover_image}}');
       }
       
       $post->delete();
       return redirect('/posts')->with('success','Post Deleted');
    }
}
