<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = ['kode', 'nama', 'deskripsi', 'rentang_skor', 'saran_penanganan', 'rujukan_helpdesk', 'created_by'];

    public function rules() { return $this->hasMany(Rule::class); }
    public function symptoms() { return $this->belongsToMany(Symptom::class, 'rules')->withPivot('mb', 'md'); }
    public function consultations() { return $this->hasMany(ConsultationResult::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
