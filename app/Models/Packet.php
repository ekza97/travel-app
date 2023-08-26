<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'airplane_id',
        'mekkah_hotel_id',
        'madinah_hotel_id',
        'title',
        'description',
        'image',
        'cost',
        'discount',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the categories that owns the Packet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get the airplanes that owns the Packet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airplanes()
    {
        return $this->belongsTo(Airplane::class, 'airplane_id', 'id');
    }

    /**
     * Get the mekkah_hotels that owns the Packet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mekkah_hotels()
    {
        return $this->belongsTo(Hotel::class, 'mekkah_hotel_id', 'id');
    }

    /**
     * Get the madinah_hotels that owns the Packet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function madinah_hotels()
    {
        return $this->belongsTo(Hotel::class, 'madinah_hotel_id', 'id');
    }
}