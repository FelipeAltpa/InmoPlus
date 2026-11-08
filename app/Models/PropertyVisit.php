<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyVisit extends Model
{
    protected $fillable = ['property_id', 'client_user_id', 'agent_user_id', 'requested_at', 'scheduled_at', 'status', 'client_message', 'agent_note'];

    protected function casts(): array
    {
        return ['requested_at' => 'datetime', 'scheduled_at' => 'datetime'];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }
}
