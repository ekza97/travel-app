<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'number',
        'fullnumber',
        'description',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the jamaahs that owns the CoverLetter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jamaahs()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id', 'id');
    }
}