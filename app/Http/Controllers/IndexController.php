<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
                'freelancer' => $service->freelancer,
                'category' => $service->category,
                'primary_image' => $service->primaryImage,
                'average_rating' => $service->averageRating(),
            ];
        });

        // dd($services[0]);
        $categories = Category::all();


 
        return Inertia::render('Index', [
            'services' => $services,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'min_price', 'max_price'])
        ]);
    }
}
