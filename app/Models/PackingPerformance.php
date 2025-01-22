<?php

namespace App\Models;

use App\Observers\PackingPerformanceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(PackingPerformanceObserver::class)]
class PackingPerformance extends Model
{
    /** @use HasFactory<\Database\Factories\PackFactory> */
    use HasFactory;

    protected $table = 'packing_performances';

    protected $fillable = [
        'person_in_charge_id',
        'admin_id',
        'timestamp',
        'gross_weight_kg',
        'qty_pack_a_0_2kg',
        'qty_pack_b_0_3kg',
        'qty_pack_c_0_4kg',
        'reject_kg'
    ];

    protected function casts(): array {
        return [
            'timestamp' => 'datetime',
            'gross_weight_kg' => 'float',
            'qty_pack_a_0_2kg' => 'int',
            'qty_pack_b_0_3kg' => 'int',
            'qty_pack_c_0_4kg' => 'int',
            'reject_kg' => 'float',
            'updated_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function pics(): BelongsTo {
        return $this->belongsTo(PersonInCharge::class, 'person_in_charge_id');
    }

    public function admin(): BelongsTo {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
