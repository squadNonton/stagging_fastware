<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianSS extends Model
{
    use HasFactory;

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_users',
        'ss_id',
        'telah_direvisi',
        'belum_diterapkan',
        'sedang_diterapkan',
        'sudah_diterapkan',
        'tidak_bisa_diterapkan',
        'ide',
        'persiapan',
        'penghematan_biaya',
        'kualitas',
        'delivery',
        'safety',
        'biaya_penerapan',
        'usaha',
        'pencapaian_target',
    ];
}
