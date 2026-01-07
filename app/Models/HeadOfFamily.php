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

    /**
     * Scope a query to search HeadOfFamilies by various fields.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
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

    /**
     * Scope a query to filter HeadOfFamilies by gender.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $gender
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGender($query, ?string $gender)
    {
        return $query->when($gender, fn($q) => $q->where('gender', $gender));
    }

    /**
     * Scope a query to filter HeadOfFamilies by family count range.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $min
     * @param int|null $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFamilyCountRange($query, ?int $min = null, ?int $max = null)
    {
        return $query->when($min !== null, fn($q) => $q->has('familyMembers', '>=', $min))
            ->when($max !== null, fn($q) => $q->has('familyMembers', '<=', $max));
    }

    /**
     * Scope a query to filter HeadOfFamilies by marital status.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $maritalStatus
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaritalStatus($query, ?string $maritalStatus)
    {
        return $query->when($maritalStatus, fn($q) => $q->where('marital_status', $maritalStatus));
    }

    /**
     * Scope a query to filter HeadOfFamilies by occupation.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $occupation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOccupation($query, ?string $occupation)
    {
        return $query->when($occupation, fn($q) => $q->where('occupation', 'like', '%' . $occupation . '%'));
    }

    /**
     * Scope a query to sort HeadOfFamilies based on specified criteria.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $sortBy
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSorted($query, ?string $sortBy)
    {
        switch ($sortBy) {
            case 'newest':
                return $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                return $query->orderBy('created_at', 'asc');
                break;
            case 'family_asc':
                return $query->withCount('familyMembers')->orderBy('family_members_count', 'asc');
                break;
            case 'family_desc':
                return $query->withCount('familyMembers')->orderBy('family_members_count', 'desc');
                break;
            case 'name_asc':
                return $query->whereHas('user', function ($q) {
                    $q->orderBy('name', 'asc');
                });
                break;
            case 'name_desc':
                return $query->whereHas('user', function ($q) {
                    $q->orderBy('name', 'desc');
                });
                break;
            default:
                return $query->orderBy('created_at', 'desc');
                break;
        }
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
