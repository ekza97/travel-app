<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_surat',
        'tujuan',
        'tanggal',
        'alamat',
        'perihal',
        'keterangan',
        'file',
        'created_by',
        'updated_by'
    ];
}