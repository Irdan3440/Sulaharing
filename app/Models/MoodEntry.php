<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    protected $fillable = ['user_id', 'mood_score', 'catatan', 'entry_date'];

    protected $casts = ['entry_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }

    public function getMoodEmojiAttribute(): string
    {
        return match($this->mood_score) {
            1 => '😢', 2 => '😔', 3 => '😐', 4 => '😊', 5 => '😄', default => '😐',
        };
    }

    public function getMoodLabelAttribute(): string
    {
        return match($this->mood_score) {
            1 => 'Sangat Buruk', 2 => 'Buruk', 3 => 'Biasa', 4 => 'Baik', 5 => 'Sangat Baik', default => 'Netral',
        };
    }
}
