<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceImageFactory> */

    use HasFactory, HasUlids;

    protected $fillable = [
        'service_id',
        'image_path',
        'is_primary',
        'order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}


