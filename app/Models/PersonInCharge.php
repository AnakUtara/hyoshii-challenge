<?php

namespace App\Models;

use App\Observers\PersonInChargeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(PersonInChargeObserver::class)]
class PersonInCharge extends Model
{
    /** @use HasFactory<\Database\Factories\PersonInChargeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'admin_id',
    ];

    protected function casts(): array {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    public function packingPerformance(): HasMany {
        return $this->hasMany(PackingPerformance::class);
    }

    public function admin(): BelongsTo {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
