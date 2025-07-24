<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('documents.index');
    }
    public function create()
    {
        return view("documents.create");
    }
    public function edit($id)
    {
        return view("documents.edit")->with('id',$id);
    }
}
