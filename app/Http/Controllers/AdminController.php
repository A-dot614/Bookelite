<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ecommerce;
class AdminController extends Controller
{
       //*************************Admin******************************* 

    public function index(){
        // load all products/books for admin listing
        $ecommerces = Ecommerce::all();
        return view('admin.books.card', compact('ecommerces'));
    }   
    
    public function carddetail(Ecommerce $shop){
        // show a single product/book in admin (route-model binding by slug)
        return view('admin.books.carddetail', compact('shop'));
    }

    public function dashboard(){
        return view('admin.dashboard');
    }

    public function form(){
        return view('admin.books.form');
    }

    public function report(){
        return view('admin.books.report');
    }

   public function customer(){
        return view('admin.books.customer');
    }

    public function postBooks(Request $request){
        // dump posted data for debug
        dd($request->all());


    }
}
