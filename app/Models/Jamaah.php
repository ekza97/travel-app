<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'agent_id',
        'schedule_id',
        'nik',
        'fullname',
        'pob',
        'dob',
        'gender',
        'phone',
        'martial_status',
        'profession',
        'address',
        'heir_name',
        'heir_relation',
        'heir_phone',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the categories that owns the Jamaah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get the agents that owns the Jamaah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agents()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    /**
     * Get the schedules that owns the Jamaah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedules()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
}
