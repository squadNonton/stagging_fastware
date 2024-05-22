<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name_customer',
        'area',
        'email',
        'no_telp',
        'status'
    ];

    public function handings(): HasMany
    {
        return $this->hasMany(Handling::class);
    }
}
