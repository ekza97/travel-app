<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'date',
        'pay',
        'amount',
        'file',
        'description',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the jamaahs that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jamaahs()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id', 'id');
    }
}