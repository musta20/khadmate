<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory , HasUlids;


    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'read'
    ];

    protected $casts = [
        'read' => 'boolean',
    ];



    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the conversation between two users.
     */
    public static function conversation($userOneId, $userTwoId)
    {
        return static::where(function ($query) use ($userOneId, $userTwoId) {
            $query->where('sender_id', $userOneId)
                  ->where('receiver_id', $userTwoId);
        })->orWhere(function ($query) use ($userOneId, $userTwoId) {
            $query->where('sender_id', $userTwoId)
                  ->where('receiver_id', $userOneId);
        })->orderBy('created_at');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead()
    {
        if (!$this->read) {
            $this->read = true;
            $this->save();
        }
        return $this;
    }

    /**
     * Mark message as unread.
     */
    public function markAsUnread()
    {
        if ($this->read) {
            $this->read = false;
            $this->save();
        }
        return $this;
    }

    /**
     * Check if message is read.
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * Get formatted time for message.
     */
    public function getFormattedTime()
    {
        $now = Carbon::now();
        $created = $this->created_at;

        if ($created->isToday()) {
            return $created->format('H:i');
        } elseif ($created->isYesterday()) {
            return 'Yesterday ' . $created->format('H:i');
        } elseif ($created->year === $now->year) {
            return $created->format('M d H:i');
        }
        
        return $created->format('M d Y H:i');
    }

    /**
     * Scope query to get unread messages.
     */
    public function scopeUnread(Builder $query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope query to get read messages.
     */
    public function scopeRead(Builder $query)
    {
        return $query->where('read', true);
    }

    /**
     * Scope query to get messages for a specific user (sent or received).
     */
    public function scopeForUser(Builder $query, $userId)
    {
        return $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
    }

    /**
     * Scope query to get messages sent by a user.
     */
    public function scopeSentBy(Builder $query, $userId)
    {
        return $query->where('sender_id', $userId);
    }

    /**
     * Scope query to get messages received by a user.
     */
    public function scopeReceivedBy(Builder $query, $userId)
    {
        return $query->where('receiver_id', $userId);
    }

    /**
     * Get unread messages count for a user.
     */
    public static function unreadCount($userId)
    {
        return static::where('receiver_id', $userId)
                    ->where('read', false)
                    ->count();
    }

    /**
     * Mark all messages in a conversation as read.
     */
    public static function markConversationAsRead($receiverId, $senderId)
    {
        return static::where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->where('read', false)
                    ->update(['read' => true]);
    }

    /**
     * Get latest message between two users.
     */
    public static function getLatestMessage($userOneId, $userTwoId)
    {
        return static::conversation($userOneId, $userTwoId)
                    ->latest()
                    ->first();
    }

    /**
     * Get all conversations for a user.
     */
    public static function getUserConversations($userId)
    {
        $sentMessages = static::where('sender_id', $userId)
                             ->select('receiver_id as user_id')
                             ->distinct();

        return static::where('receiver_id', $userId)
                    ->select('sender_id as user_id')
                    ->distinct()
                    ->union($sentMessages)
                    ->get()
                    ->pluck('user_id');
    }

    /**
     * Get conversation summary between two users.
     */
    public static function getConversationSummary($userOneId, $userTwoId)
    {
        $conversation = static::conversation($userOneId, $userTwoId);
        
        return [
            'message_count' => $conversation->count(),
            'unread_count' => $conversation->where('read', false)
                                         ->where('receiver_id', $userOneId)
                                         ->count(),
            'latest_message' => $conversation->latest()->first(),
            'started_at' => $conversation->oldest()->first()?->created_at,
        ];
    }

    /**
     * Check if a user is involved in the message.
     */
    public function involvedUser($userId)
    {
        return $this->sender_id === $userId || $this->receiver_id === $userId;
    }

    /**
     * Get preview of message content.
     */
    public function getPreview($length = 50)
    {
        return strlen($this->content) > $length
            ? substr($this->content, 0, $length) . '...'
            : $this->content;
    }


}
