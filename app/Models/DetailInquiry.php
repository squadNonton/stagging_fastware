<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailInquiry extends Model
{
    use HasFactory;

    protected $table = 'detail_inquiry'; // Pastikan nama tabel benar
    protected $fillable = [
        'id_inquiry',
        'nama_material',
        'jenis',
        'thickness',
        'weight',
        'length',
        'qty',
        'pcs',
    ];

    public function inquirySale()
    {
        return $this->belongsTo(InquirySales::class);
    }
}
