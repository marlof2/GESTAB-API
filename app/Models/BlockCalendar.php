<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

final class BlockCalendar extends Model
{
    protected $table = 'block_calendar';
    protected $guarded = ['id'];
    protected $fillable = ["establishment_id","user_id","period","date","time_start","time_end"];

    public function establishment() : BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
