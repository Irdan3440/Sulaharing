<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiometricData extends Model
{
    protected $fillable = ['user_id', 'heart_rate', 'hrv', 'spo2', 'status', 'device_id', 'recorded_at'];

    protected $casts = ['recorded_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public function getIsAnomalyAttribute(): bool
    {
        return $this->heart_rate > 100 || $this->heart_rate < 50 || ($this->spo2 !== null && $this->spo2 < 95);
    }
}
