<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory , HasUlids;

    protected $fillable = [
        'service_id',
        'client_id',
        'freelancer_id',
        'status',
        'amount',
        'due_date'
    ];


    protected $casts = [
        'due_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $statuses = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED
    ];

    /**
     * Get the service associated with the order.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the client who placed the order.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the freelancer who received the order.
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Get the reviews for this order.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the client's review for this order.
     */
    public function clientReview()
    {
        return $this->hasOne(Review::class)->where('reviewer_id', $this->client_id);
    }

    /**
     * Get the freelancer's review for this order.
     */
    public function freelancerReview()
    {
        return $this->hasOne(Review::class)->where('reviewer_id', $this->freelancer_id);
    }

    /**
     * Get messages related to this order.
     */
    public function messages()
    {
        return Message::where(function ($query) {
            $query->where('sender_id', $this->client_id)
                  ->where('receiver_id', $this->freelancer_id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->freelancer_id)
                  ->where('receiver_id', $this->client_id);
        })->orderBy('created_at');
    }

    /**
     * Check if order is overdue.
     */
    public function isOverdue()
    {
        return $this->status !== self::STATUS_COMPLETED 
            && $this->status !== self::STATUS_CANCELLED 
            && $this->due_date < Carbon::now();
    }

    /**
     * Get days remaining until due date.
     */
    public function daysRemaining()
    {
        return $this->status !== self::STATUS_COMPLETED 
            && $this->status !== self::STATUS_CANCELLED
            ? Carbon::now()->diffInDays($this->due_date, false)
            : 0;
    }

    /**
     * Update order status.
     */
    public function updateStatus($status)
    {
        if (!in_array($status, $this->statuses)) {
            throw new \InvalidArgumentException('Invalid status');
        }

        $this->status = $status;
        $this->save();

        return $this;
    }

    /**
     * Scope query to get orders by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope query to get active orders (pending or in progress).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Scope query to get completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope query to get cancelled orders.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope query to get overdue orders.
     */
    public function scopeOverdue($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_IN_PROGRESS])
                    ->where('due_date', '<', Carbon::now());
    }

    /**
     * Check if reviews are allowed for this order.
     */
    public function canBeReviewed()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Get order progress status.
     */
    public function getProgress()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 0;
            case self::STATUS_IN_PROGRESS:
                $totalDays = Carbon::parse($this->created_at)->diffInDays($this->due_date);
                $daysLeft = $this->daysRemaining();
                return min(95, max(5, (($totalDays - $daysLeft) / $totalDays) * 100));
            case self::STATUS_COMPLETED:
                return 100;
            default:
                return 0;
        }
    }

    /**
     * Mark order as completed.
     */
    public function complete()
    {
        return $this->updateStatus(self::STATUS_COMPLETED);
    }

    /**
     * Mark order as cancelled.
     */
    public function cancel()
    {
        return $this->updateStatus(self::STATUS_CANCELLED);
    }

    /**
     * Start the order (change status to in_progress).
     */
    public function start()
    {
        return $this->updateStatus(self::STATUS_IN_PROGRESS);
    }

    /**
     * Check if user is involved in this order.
     */
    public function involvedUser($userId)
    {
        return $this->client_id === $userId || $this->freelancer_id === $userId;
    }

}
