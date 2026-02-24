<?php

namespace App\Http\Controllers;
use App\Models\Ecommerce;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{
    //***************************site******************************
    public function index(){
        $ecommerces = Ecommerce::all();
        return view('site.home', compact('ecommerces'));
    }
    
    public function detail(Ecommerce $shop){
        return view('site.detail', compact('shop'));
    }

    public function service(){
        return view('site.service');
    }

    public function about(){
        return view('site.about');
    }

    public function contact(){
        return view('site.contact');
    }


 

}