<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){
        $title="Welcome to Laravel !!";
        return view('pages.index')->with('title',$title);
    }

    public function services(){
        $data=array(
            'title'=>'Services',
            'services'=>['WEB','Programming'],
        );
        return view('pages.services')->with($data);
    }

    public function about(){
        $title="About";
        return view('pages.about')->with('title',$title);
    }
}
