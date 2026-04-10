<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationResult extends Model
{
    protected $fillable = [
        'user_id', 'disease_id', 'guest_name', 'guest_institusi', 'guest_usia',
        'is_guest', 'total_score_bdi', 'cf_result', 'cf_percentage',
        'classification', 'cf_detail', 'saran', 'consulted_at',
    ];

    protected $casts = [
        'is_guest' => 'boolean',
        'cf_result' => 'decimal:4',
        'cf_percentage' => 'decimal:1',
        'cf_detail' => 'array',
        'consulted_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function disease() { return $this->belongsTo(Disease::class); }
    public function details() { return $this->hasMany(ConsultationDetail::class); }

    public function getClassSlugAttribute(): string
    {
        return match(strtolower($this->classification)) {
            'minimal' => 'minimal',
            'ringan' => 'ringan',
            'sedang' => 'sedang',
            default => 'berat',
        };
    }
}
