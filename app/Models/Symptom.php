<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = ['kode', 'nama', 'deskripsi', 'aspek', 'pilihan_jawaban', 'created_by'];

    protected $casts = ['pilihan_jawaban' => 'array'];

    public function rules() { return $this->hasMany(Rule::class); }
    public function diseases() { return $this->belongsToMany(Disease::class, 'rules')->withPivot('mb', 'md'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
