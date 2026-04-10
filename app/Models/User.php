<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'nim', 'fakultas', 'jurusan',
        'role', 'avatar_url', 'is_active', 'last_login',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // --- Scopes ---
    public function scopeMahasiswa($query) { return $query->where('role', 'mahasiswa'); }
    public function scopePakar($query) { return $query->where('role', 'pakar'); }
    public function scopeAdmin($query) { return $query->where('role', 'admin'); }

    // --- Relationships ---
    public function consultations() { return $this->hasMany(ConsultationResult::class); }
    public function moodEntries() { return $this->hasMany(MoodEntry::class); }
    public function biometricData() { return $this->hasMany(BiometricData::class); }
    public function iotAlerts() { return $this->hasMany(IotAlert::class); }

    // --- Helpers ---
    public function isMahasiswa(): bool { return $this->role === 'mahasiswa'; }
    public function isPakar(): bool { return $this->role === 'pakar'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function getInitialsAttribute(): string { return strtoupper(substr($this->name, 0, 1)); }

    public function latestConsultation()
    {
        return $this->hasOne(ConsultationResult::class)->latestOfMany();
    }

    public function getMoodStreakAttribute(): int
    {
        $streak = 0;
        $date = now()->toDateString();
        while ($this->moodEntries()->where('entry_date', $date)->exists()) {
            $streak++;
            $date = now()->subDays($streak)->toDateString();
        }
        return $streak;
    }
}
