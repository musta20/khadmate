<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Service;

class DashboardController extends Controller
{
   
  public function index(){

    return Inertia::render('Dashboard/Dashboard');
    
  }

  public function servicesList(){
    $user = Auth::user();
    $services = Service::where('user_id', $user->id)->paginate(10);
     return Inertia::render('Dashboard/Services', [
        'services' => $services
    ]);
    
  }
  
}
