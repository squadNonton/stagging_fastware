<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SumbangSaran extends Model
{
    use HasFactory;
    protected $table = 'sumbang_sarans';
    protected $fillable = [
        'id_user',
        'tgl_pengajuan_ide',
        'lokasi_ide',
        'tgl_diterapkan',
        'judul',
        'keadaan_sebelumnya',
        'image',
        'usulan_ide',
        'image_2',
        'keuntungan_ide',
        'status',
        'modified_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(PenilaianSS::class);
    }
}
