<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory , HasUlids;

    protected $fillable = ['name', 'slug', 'description'];

       /**
     * Get all services in this category.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get active services in this category.
     */
    public function activeServices()
    {
        return $this->services()->where('status', 'active');
    }

    /**
     * Get all freelancers who have services in this category.
     */
    public function freelancers()
    {
        return User::whereHas('services', function ($query) {
            $query->where('category_id', $this->id);
        })->freelancers();
    }

    /**
     * Get the number of services in this category.
     */
    public function serviceCount()
    {
        return $this->services()->count();
    }

    /**
     * Get the number of active services in this category.
     */
    public function activeServiceCount()
    {
        return $this->activeServices()->count();
    }

    /**
     * Get the average service price in this category.
     */
    public function averageServicePrice()
    {
        return $this->services()->avg('price');
    }

    /**
     * Get completed orders count for services in this category.
     */
    public function completedOrdersCount()
    {
        return Order::whereHas('service', function ($query) {
            $query->where('category_id', $this->id);
        })->where('status', 'completed')->count();
    }

    /**
     * Generate a slug from the name.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Scope a query to get categories with active services.
     */
    public function scopeWithActiveServices($query)
    {
        return $query->whereHas('services', function ($query) {
            $query->where('status', 'active');
        });
    }

    /**
     * Scope a query to order categories by service count.
     */
    public function scopeOrderByServiceCount($query, $direction = 'desc')
    {
        return $query->withCount('services')->orderBy('services_count', $direction);
    }

    /**
     * Get popular categories based on service count.
     */
    public static function getPopular($limit = 5)
    {
        return static::withCount('services')
            ->orderBy('services_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Calculate category statistics
     */
    public function getStatistics()
    {
        return [
            'total_services' => $this->serviceCount(),
            'active_services' => $this->activeServiceCount(),
            'average_price' => $this->averageServicePrice(),
            'completed_orders' => $this->completedOrdersCount(),
            'freelancer_count' => $this->freelancers()->count(),
        ];
    }
    
}
