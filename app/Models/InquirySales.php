<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquirySales extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_inquiry',
        'jenis_inquiry',
        'type',
        'size',
        'supplier',
        'qty',
        'order_from',
        'create_by',
        'to_approve',
        'to_validate',
        'note',
        'attach_file',
    ];
}
