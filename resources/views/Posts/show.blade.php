@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-default">Go Back</a> 
    <h1>{{$post->title}}</h1>    
    <img style="width:100%" src="/storage/coverImages/{{$post->cover_image}}">
    <div class="well">
        <small>Written On {{$post->created_at}}</small>
        <h3>Body</h3>
        <p>{!!$post->body!!}</p>  <!--One Curly Brace and !!-->
        <hr>    
        <small>Modified At {{$post->updated_at}}</small>
    </div>         
    @if(!Auth::guest())        
        <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>
        {!!Form::open(['action' => ['PostController@destroy',$post->id],'method'=>'POST','class' => 'pull-right'])!!}
            {{Form::hidden('_method','DELETE')}}
            {{Form::submit('Delete',['class'=>'btn btn-danger'])}}        
        {!!Form::close()!!}
    @endif    
@endsection