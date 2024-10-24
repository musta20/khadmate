<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Create a new controller instance.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['index', 'show']);
    // }

    /**
     * Display a listing of the services.
     */
    public function index(Request $request)
    {
        $query = Service::with(['freelancer', 'category', 'primaryImage'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($request->min_price, function ($query, $price) {
                $query->where('price', '>=', $price);
            })
            ->when($request->max_price, function ($query, $price) {
                $query->where('price', '<=', $price);
            })
            ->latest();

        $services = $query->paginate(12)->withQueryString();

        return Inertia::render('Services/Index', [
            'services' => $services,
            'filters' => $request->only(['search', 'category', 'min_price', 'max_price']),
            'categories' => Category::all()
        ]);
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $this->authorize('create', Service::class);

        return Inertia::render('Services/Create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Service::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'delivery_time' => 'required|integer|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $service = Auth::user()->services()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'delivery_time' => $validated['delivery_time'],
            'status' => 'active'
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('services', 'public');
                $service->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index
                ]);
            }
        }

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        $service->load([
            'freelancer',
            'category',
            'images',
            'reviews' => fn($query) => $query->with('reviewer')->latest()->limit(5)
        ]);

        return Inertia::render('Services/Show', [
            'service' => array_merge($service->toArray(), [
                'primary_image_path' => $service->primary_image_path,
                'average_rating' => $service->averageRating(),
                'total_reviews' => $service->totalReviews(),
                'rating_distribution' => $service->getRatingDistribution(),
                'statistics' => $service->getStatistics(),
 
            ]),
            'can' => [
                'update' => Auth::user()?->can('update', $service) ?? false,
                'delete' => Auth::user()?->can('delete', $service) ?? false,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $this->authorize('update', $service);

        return Inertia::render('Services/Edit', [
            'service' => array_merge($service->load('images')->toArray(), [
                'primary_image_path' => $service->primary_image_path
            ]),
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'delivery_time' => 'required|integer|min:1',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $service->update($validated);

        if ($request->hasFile('images')) {
            // Delete old images if requested
            if ($request->boolean('replace_images')) {
                foreach ($service->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $service->images()->delete();
            }

            // Add new images
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('services', 'public');
                $service->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0 && $request->boolean('replace_images'),
                    'order' => $service->images()->count() + $index
                ]);
            }
        }

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        // Delete images from storage
        foreach ($service->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Toggle the service status.
     */
    public function toggleStatus(Service $service)
    {
        $this->authorize('update', $service);

        $service->status = $service->status === 'active' ? 'paused' : 'active';
        $service->save();

        return back()->with('success', 'Service status updated successfully.');
    }
}