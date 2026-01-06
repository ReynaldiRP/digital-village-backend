<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeadOfFamily extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'identify_number',
        'gender',
        'birth_date',
        'phone_number',
        'occupation',
        'marital_status',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '&' . $search . '%');
        })->orWhere('identify_number', 'like', '%' . $search . '%')
            ->orWhere('gender', 'like', '%' . $search . '%')
            ->orWhere('occupation', 'like', '%' . $search . '%')
            ->orWhere('marital_status', 'like', '%' . $search . '%');
    }

    public function scopeGender($query, ?string $gender)
    {
        return $query->when($gender, fn($q) => $q->where('gender', $gender));
    }

    public function scopeFamilyCountRange($query, ?int $min = null, ?int $max = null)
    {
        return $query->when($min !== null, fn($q) => $q->has('familyMembers', '>=', $min))
            ->when($max !== null, fn($q) => $q->has('familyMembers', '<=', $max));
    }

    public function scopeMaritalStatus($query, ?string $maritalStatus)
    {
        return $query->when($maritalStatus, fn($q) => $q->where('marital_status', $maritalStatus));
    }

    public function scopeOccupation($query, ?string $occupation)
    {
        return $query->when($occupation, fn($q) => $q->where('occupation', 'like', '%' . $occupation . '%'));
    }

    public function scopeSorted($query, ?string $sortBy, ?string $sortOrder)
    {
        return $query->when($sortBy, fn($q) => $q->orderBy($sortBy, $sortOrder ?? 'asc'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class, 'head_of_family_id', 'id');
    }

    public function socialAssistanceRecipients(): HasMany
    {
        return $this->hasMany(SocialAssistanceRecipient::class, 'head_of_family_id', 'id');
    }

    public function eventParticipants(): HasMany
    {
        return $this->hasMany(EventParticipant::class, 'head_of_family_id', 'id');
    }
}
