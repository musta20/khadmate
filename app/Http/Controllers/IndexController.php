<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::with(['freelancer', 'category', 'primaryImage'])
        ->active()
        // ->when($request->search, function ($query, $search) {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('title', 'like', "%{$search}%")
        //           ->orWhere('description', 'like', "%{$search}%");
        //     });
        // })
       // ->take(12)->get();
        ->paginate(12)
        ->through(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'price' => $service->price,
                'freelancer' =>$service->freelancer,
                  
                'category' => $service->category,
                'primary_image' => $service->primaryImage,
                'average_rating' => number_format($service->averageRating(), 1),
            ];
        });

        // dd($services[0]);
        $categories = Category::all();
 
        // Assuming you want to pass some session data to the view
        $sessionData = [
            'user' => Session::get('user'),
            'isLoggedIn' => Session::has('user'),
            // Add any other session data you want to pass
        ];

        return Inertia::render('Index', [
            'services' => $services,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'min_price', 'max_price']),
            'sessionData' => $sessionData,
        ]);
    }


    public function serviceShow(Service $service)
    {
        $service->load([
            'category',
            'images',
            'reviews' => fn($query) => $query->with('reviewer')->latest()->limit(5)
        ]);
       
       $freelancer = [
        'id' => $service->freelancer->id,
        'name' => $service->freelancer->name,
        'average_rating' => $service->freelancer->reviewsReceived(),
        'avatar' => $service->freelancer->avatar,
        ];

          return Inertia::render('Services/Show', [
            'service' => array_merge($service->toArray(), [
              'freelancer'  =>  $freelancer,
                'primary_image' => $service->primaryImage,
                'average_rating' => number_format($service->averageRating(), 1),
                'total_reviews' => $service->totalReviews(),
                'rating_distribution' => $service->getRatingDistribution(),
                'statistics' => $service->getStatistics(),
                'images'=>$service->images
            ]),
            // 'can' => [
            //     'update' => Auth::user()?->can('update', $service) ?? false,
            //     'delete' => Auth::user()?->can('delete', $service) ?? false,
            // ]
        ]);
    }







}
