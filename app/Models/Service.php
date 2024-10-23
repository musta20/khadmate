<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory , HasUlids;


    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'delivery_time',
        'status'
    ];



    protected $casts = [
        'price' => 'decimal:2',
        'delivery_time' => 'integer',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';
    const STATUS_DELETED = 'deleted';


    /**
     * Get all images for the service.
     */
    public function images()
    {
        return $this->hasMany(ServiceImage::class)->orderBy('order');
    }

    /**
     * Get the primary image for the service.
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ServiceImage::class)
            ->where('is_primary', true);    
    }

    /**
     * Get the primary image path or default image.
     */
//     public function getPrimaryImageAttribute()
//     {
//   // Load the relationship if it hasn't been loaded
//         if (!$this->relationLoaded('primaryImage')) {
//             $this->load('primaryImage');
//         }

//         if (!$this->relationLoaded('images')) {
//             $this->load('images');
//         }

//         // Check for primary image
//         if ($this->primaryImage) {
//             return $this->primaryImage->image_path;
//         }

//         // Check for any image
//         if ($this->images->isNotEmpty()) {
//             return $this->images->first()->image_path;
//         }

//         // Return default image
//         return 'images/default-service-image.jpg';
//     }

    /**
     * Set the primary image for the service.
     */
    public function setPrimaryImage($imageId)
    {
        $this->images()->update(['is_primary' => false]);
        $this->images()->where('id', $imageId)->update(['is_primary' => true]);
    }
    /**
     * Get the freelancer who owns the service.
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category of the service.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the orders for the service.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the reviews for the service through orders.
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Order::class);
    }

    /**
     * Get active orders for the service.
     */
    public function activeOrders()
    {
        return $this->orders()->whereIn('status', ['pending', 'in_progress']);
    }

    /**
     * Get completed orders for the service.
     */
    public function completedOrders()
    {
        return $this->orders()->where('status', 'completed');
    }

    /**
     * Scope query to only include active services.
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope query to only include paused services.
     */
    public function scopePaused(Builder $query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }

    /**
     * Scope query to filter by category.
     */
    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope query to filter by price range.
     */
    public function scopePriceRange(Builder $query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope query to filter by delivery time.
     */
    public function scopeDeliveryTime(Builder $query, $days)
    {
        return $query->where('delivery_time', '<=', $days);
    }

    /**
     * Scope query to search services.
     */
    public function scopeSearch(Builder $query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('title', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Get the average rating for the service.
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get the total number of reviews.
     */
    public function totalReviews()
    {
        return $this->reviews()->count();
    }

    /**
     * Get rating distribution.
     */
    public function getRatingDistribution()
    {
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        $this->reviews()
            ->select('rating')
            ->get()
            ->each(function ($review) use (&$distribution) {
                $distribution[$review->rating]++;
            });

        return $distribution;
    }

    /**
     * Get service statistics.
     */
    public function getStatistics()
    {
        return [
            'total_orders' => $this->orders()->count(),
            'active_orders' => $this->activeOrders()->count(),
            'completed_orders' => $this->completedOrders()->count(),
            'total_earnings' => $this->completedOrders()->sum('amount'),
            'average_rating' => $this->averageRating(),
            'total_reviews' => $this->totalReviews(),
            'rating_distribution' => $this->getRatingDistribution(),
        ];
    }

    /**
     * Check if service is active.
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if service is paused.
     */
    public function isPaused()
    {
        return $this->status === self::STATUS_PAUSED;
    }

    /**
     * Check if service is deleted.
     */
    public function isDeleted()
    {
        return $this->status === self::STATUS_DELETED;
    }

    /**
     * Activate the service.
     */
    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
        return $this;
    }

    /**
     * Pause the service.
     */
    public function pause()
    {
        $this->status = self::STATUS_PAUSED;
        $this->save();
        return $this;
    }

    /**
     * Mark service as deleted.
     */
    public function markAsDeleted()
    {
        $this->status = self::STATUS_DELETED;
        $this->save();
        return $this;
    }

    /**
     * Format price with currency.
     */
    public function getFormattedPrice($currency = '$')
    {
        return $currency . number_format($this->price, 2);
    }

    /**
     * Get delivery time text.
     */
    public function getDeliveryTimeText()
    {
        return $this->delivery_time . ' ' . Str::plural('day', $this->delivery_time);
    }

    /**
     * Check if user can order this service.
     */
    public function canBeOrdered()
    {
        return $this->isActive() && 
               $this->freelancer->isFreelancer() && 
               !$this->activeOrders()->exists();
    }

    /**
     * Get similar services.
     */
    public function getSimilarServices($limit = 5)
    {
        return static::active()
            ->where('id', '!=', $this->id)
            ->where('category_id', $this->category_id)
            ->limit($limit)
            ->get();
    }






}
