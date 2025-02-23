<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class GoogleCalendarEvent extends Model
{
    use HasFactory;
    protected $table = 'google_calendar_events';

    protected $fillable = [
        'google_event_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'client_email',
        'user_id',
        'list_id',
        'html_link'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
