<?php

namespace App\Http\Controllers;

class ZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('zone.index');
    }
    public function edit($id)
    {
        return view('zone.edit')->with('id',$id);
    }

    public function create()
    {
        return view('zone.create');
    }
}