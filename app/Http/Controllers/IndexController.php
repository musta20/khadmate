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
        $query = Service::with(['freelancer', 'category'])
            ->active();
            // ->when($request->category, function ($query, $category) {
            //     $query->where('category_id', $category);
            // })
            // ->when($request->search, function ($query, $search) {
            //     $query->where(function ($q) use ($search) {
            //         $q->where('title', 'like', "%{$search}%")
            //           ->orWhere('description', 'like', "%{$search}%");
            //     });
            // })
            // ->when($request->min_price, function ($query, $minPrice) {
            //     $query->where('price', '>=', $minPrice);
            // })
            // ->when($request->max_price, function ($query, $maxPrice) {
            //     $query->where('price', '<=', $maxPrice);
            // });

        $services = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return Inertia::render('Index', [
            'services' => $services,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'min_price', 'max_price'])
        ]);
    }
}
