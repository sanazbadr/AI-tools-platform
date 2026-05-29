<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArcheoController extends Controller
{
    public function index(): View
    {
        return view('archeo.home');
    }
    
    public function about(): View
    {
        return view('archeo.about');
    }
    
    public function services(): View
    {
        return view('archeo.services');
    }
    
    public function contact(): View
    {
        return view('archeo.contact');
    }
} 