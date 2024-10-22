<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasUlids;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'role',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    /**
     * Get the services that the user has created as a freelancer.
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    /**
     * Get the orders that the user has made as a client.
     */
    public function clientOrders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    /**
     * Get the orders that the user has received as a freelancer.
     */
    public function freelancerOrders()
    {
        return $this->hasMany(Order::class, 'freelancer_id');
    }

    /**
     * Get all orders related to the user (both as client and freelancer).
     */
    public function allOrders()
    {
        return $this->clientOrders->merge($this->freelancerOrders);
    }

    /**
     * Get the reviews that the user has written.
     */
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Get the reviews that the user has received.
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    /**
     * Get messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by the user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get all messages related to the user (both sent and received).
     */
    public function allMessages()
    {
        return $this->sentMessages->merge($this->receivedMessages);
    }

    /**
     * Get unread messages for the user.
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()->where('read', false);
    }

    /**
     * Scope a query to only include freelancer users.
     */
    public function scopeFreelancers($query)
    {
        return $query->where('role', 'freelancer');
    }

    /**
     * Scope a query to only include client users.
     */
    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    /**
     * Check if the user is a freelancer.
     */
    public function isFreelancer()
    {
        return $this->role === 'freelancer';
    }

    /**
     * Check if the user is a client.
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Get the average rating received by the user.
     */
    public function averageRating()
    {
        return $this->reviewsReceived()->avg('rating');
    }
}
