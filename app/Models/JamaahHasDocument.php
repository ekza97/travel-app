<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamaahHasDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'document_id',
        'file',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the jamaahs that owns the JamaahHasDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jamaahs()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id', 'id');
    }

    /**
     * Get the documents that owns the JamaahHasDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documents()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}