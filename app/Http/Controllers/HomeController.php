<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        
    }
    public function index() 
    {   
        return view('home.index');
    }

    public function dashboard()
    {
        // Cukup satu baris ini:
        return redirect()->route('sku.dashboard');
    }
}
