<?php

namespace App\Http\Controllers;

class CuisineController extends Controller
{   

    public function __construct()
    {
        $this->middleware('auth');
    }
    
	  public function index()
    {
        return view("cuisines.index");
        
    }

     public function edit($id)
    {
    	return view('cuisines.edit')->with('id', $id);
    }

    public function create()
    {
        return view('cuisines.create');
    }

}


