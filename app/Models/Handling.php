<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Handling extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_wo',
        'user_id',
        'customer_id',
        'type_id',
        'thickness',
        'weight',
        'outer_diameter',
        'inner_diameter',
        'length',
        'qty',
        'pcs',
        'category',
        'results',
        'process_type',
        'type_1',
        'type_2',
        'image',
        'status',
        'status_2',
        'modified_by',
    ];

    public static function countDataBetweenDates($start_date, $end_date)
    {
        return self::whereBetween('created_at', [$start_date, $end_date])
            ->where('status_2', 0)
            ->where('status', 3)
            ->count();
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function type_materials(): BelongsTo
    {
        return $this->belongsTo(TypeMaterial::class, 'type_id');
    }

    public function schedule_viist(): HasMany
    {
        return $this->hasMany(ScheduleVisit::class);
    }
}
