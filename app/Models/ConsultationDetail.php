<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationDetail extends Model
{
    protected $fillable = [
        'consultation_result_id', 'symptom_id', 'answer_index', 'answer_score',
        'cf_user', 'cf_pakar_mb', 'cf_pakar_md', 'cf_combine',
    ];

    protected $casts = [
        'cf_user' => 'decimal:2',
        'cf_pakar_mb' => 'decimal:2',
        'cf_pakar_md' => 'decimal:2',
        'cf_combine' => 'decimal:4',
    ];

    public function consultationResult() { return $this->belongsTo(ConsultationResult::class); }
    public function symptom() { return $this->belongsTo(Symptom::class); }
}
