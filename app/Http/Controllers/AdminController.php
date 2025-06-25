<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Addon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }
    
}
