<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'tujuan',
        'no_surat',
        'alamat',
        'tanggal',
        'keterangan',
        'perihal',
        'file',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the jenis_surat that owns the SuratMasuk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis_surat()
    {
        return $this->belongsTo(MasterSurat::class);
    }
}