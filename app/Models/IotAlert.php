<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IotAlert extends Model
{
    protected $fillable = ['user_id', 'alert_type', 'severity', 'message', 'data', 'is_read', 'alerted_at'];

    protected $casts = ['data' => 'array', 'is_read' => 'boolean', 'alerted_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}
