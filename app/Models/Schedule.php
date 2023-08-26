<?php

namespace App\Models;

use App\Models\Packet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'packet_id',
        'name',
        'day',
        'description',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the packets that owns the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function packets()
    {
        return $this->belongsTo(Packet::class, 'packet_id', 'id');
    }
}