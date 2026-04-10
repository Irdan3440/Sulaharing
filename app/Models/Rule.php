<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = ['symptom_id', 'disease_id', 'mb', 'md', 'updated_by'];

    protected $casts = ['mb' => 'decimal:2', 'md' => 'decimal:2'];

    public function symptom() { return $this->belongsTo(Symptom::class); }
    public function disease() { return $this->belongsTo(Disease::class); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }

    public function getCfAttribute(): float { return $this->mb - $this->md; }
}
