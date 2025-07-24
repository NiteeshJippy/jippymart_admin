<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SubscriptionPlanController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        
        return view("subscription_plans.index");
    }

    public function save($id='')
    {
        return view("subscription_plans.save")->with('id',$id);
    }
   
}
