<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory , HasUlids;


    protected $fillable = ['rating','comment'];


    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Rating validation rules
     */
    const MIN_RATING = 1;
    const MAX_RATING = 5;

    /**
     * Get the order associated with the review.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the service associated with the review through order.
     */
    public function service()
    {
        return $this->hasOneThrough(
            Service::class,
            Order::class,
            'id', // Order key
            'id', // Service key
            'order_id', // Review key
            'service_id' // Order key
        );
    }

    /**
     * Get the user who wrote the review (reviewer).
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the user who received the review (reviewee).
     */
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    /**
     * Check if the review is from a client.
     */
    public function isClientReview()
    {
        return $this->order->client_id === $this->reviewer_id;
    }

    /**
     * Check if the review is from a freelancer.
     */
    public function isFreelancerReview()
    {
        return $this->order->freelancer_id === $this->reviewer_id;
    }

    /**
     * Get review type (client or freelancer).
     */
    public function getReviewType()
    {
        return $this->isClientReview() ? 'client' : 'freelancer';
    }

    /**
     * Scope query to get client reviews.
     */
    public function scopeClientReviews(Builder $query)
    {
        return $query->whereHas('order', function ($q) {
            $q->whereColumn('client_id', 'reviewer_id');
        });
    }

    /**
     * Scope query to get freelancer reviews.
     */
    public function scopeFreelancerReviews(Builder $query)
    {
        return $query->whereHas('order', function ($q) {
            $q->whereColumn('freelancer_id', 'reviewer_id');
        });
    }

    /**
     * Scope query to get reviews by rating.
     */
    public function scopeByRating(Builder $query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope query to get reviews with rating greater than or equal to.
     */
    public function scopeMinRating(Builder $query, int $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope query to get reviews for a specific service.
     */
    public function scopeForService(Builder $query, $serviceId)
    {
        return $query->whereHas('order', function ($q) use ($serviceId) {
            $q->where('service_id', $serviceId);
        });
    }

    /**
     * Get the formatted rating (e.g., "4/5").
     */
    public function getFormattedRating()
    {
        return "{$this->rating}/" . self::MAX_RATING;
    }

    /**
     * Get rating as percentage.
     */
    public function getRatingPercentage()
    {
        return ($this->rating / self::MAX_RATING) * 100;
    }

    /**
     * Validate rating value.
     */
    public static function isValidRating($rating)
    {
        return $rating >= self::MIN_RATING && $rating <= self::MAX_RATING;
    }

    /**
     * Boot method to add validation.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($review) {
            if (!static::isValidRating($review->rating)) {
                throw new \InvalidArgumentException(
                    "Rating must be between " . self::MIN_RATING . " and " . self::MAX_RATING
                );
            }
        });
    }

    /**
     * Check if review can be edited.
     */
    public function canBeEdited()
    {
        // Reviews can be edited within 7 days of creation
        return $this->created_at->addDays(7)->isFuture();
    }

    /**
     * Get the sentiment of the review based on rating.
     */
    public function getSentiment()
    {
        if ($this->rating >= 4) return 'positive';
        if ($this->rating >= 3) return 'neutral';
        return 'negative';
    }

    /**
     * Get reviews summary for a reviewee.
     */
    public static function getRevieweeSummary($revieweeId)
    {
        $reviews = static::where('reviewee_id', $revieweeId);
        
        return [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rating'),
            'rating_distribution' => [
                5 => $reviews->where('rating', 5)->count(),
                4 => $reviews->where('rating', 4)->count(),
                3 => $reviews->where('rating', 3)->count(),
                2 => $reviews->where('rating', 2)->count(),
                1 => $reviews->where('rating', 1)->count(),
            ],
        ];
    }



}
