<?php

namespace App\Models;

use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'source',
        'owner',
        'created_by',
        'created_at'
    ];

    /**
     * @return LeadFactory|Factory
     */
    protected static function newFactory(): LeadFactory|Factory
    {
        return LeadFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeOfOwner($query, $value)
    {
        if (is_array($value) && !empty($value)) {
            return $query->whereIn('leads.owner', $value);
        }
        return !$value ? $query : $query->where('leads.owner', $value);
    }

}
