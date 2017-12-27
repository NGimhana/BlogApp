@extends('layouts.app')

@section('content')
<H1>{{$title}}</H1>
    @if (count($services)>0)
        @foreach($services as $service)
            <ul>
                <li>{{$service}}</li>
            </ul>
        @endforeach
    @endif
    
@endsection