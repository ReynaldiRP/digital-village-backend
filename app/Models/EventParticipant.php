<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventParticipant extends Model
{
    use UUID, SoftDeletes, HasFactory;

    protected $fillable = [
        'event_id',
        'head_of_family_id',
        'quantity',
        'total_price',
        'payment_status',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function headOfFamily(): BelongsTo
    {
        return $this->belongsTo(HeadOfFamily::class, 'head_of_family_id');
    }

    /**
     * Calculate total participants (head of family + family members).
     * 
     * @return int
     */
    public function getTotalParticipantsAttribute(): int
    {
        if (!$this->head_of_family_id) {
            return 0;
        }

        // Try to get count from loaded relationship first (more efficient)
        if ($this->relationLoaded('headOfFamily') && $this->headOfFamily) {
            $familyMembersCount = $this->headOfFamily->relationLoaded('familyMembers')
                ? $this->headOfFamily->familyMembers->count()
                : $this->headOfFamily->familyMembers()->count();

            return 1 + $familyMembersCount;
        }

        // Fallback: query directly from database
        $familyMembersCount = \App\Models\FamilyMember::where('head_of_family_id', $this->head_of_family_id)->count();

        return 1 + $familyMembersCount;
    }
}
