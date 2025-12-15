<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'title',
        'category',
        'description',
        'start_at',
        'end_at',
        'location',
        'is_published',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'start_at'     => 'datetime',
        'end_at'       => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * Otomatis ikut diserialisasi saat toArray()/JSON
     */
    protected $appends = [
        'start_at_formatted',
        'end_at_formatted',
        'status',
        'duration_human',
    ];

    /* =========================================================
     | ACCESSORS
     ==========================================================*/

    public function getStartAtFormattedAttribute(): string
    {
        return $this->start_at ? $this->start_at->format('d M Y, H:i') : '-';
    }

    public function getEndAtFormattedAttribute(): string
    {
        return $this->end_at ? $this->end_at->format('d M Y, H:i') : '-';
    }

    public function getStatusAttribute(): ?string
    {
        $start = $this->start_at;
        $end   = $this->end_at;

        if (!$start) return null;

        $now = now();

        if ($start->isFuture()) {
            return 'upcoming';
        }

        if ($end) {
            if ($start->lte($now) && $end->gte($now)) return 'ongoing';
            if ($end->lt($now)) return 'past';
        } else {
            if ($start->isSameDay($now)) return 'ongoing';
            if ($start->lt($now)) return 'past';
        }

        return null;
    }

    public function getDurationHumanAttribute(): ?string
    {
        if (!$this->start_at || !$this->end_at) return null;

        $seconds = $this->end_at->diffInSeconds($this->start_at, true);
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours && $minutes) return "{$hours} jam {$minutes} menit";
        if ($hours) return "{$hours} jam";
        if ($minutes) return "{$minutes} menit";
        return "{$seconds} detik";
    }

    /* =========================================================
     | RELATIONSHIPS
     ==========================================================*/

    /**
     * ✅ Relationship: Activity dapat diikuti oleh banyak users
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'activity_participants')
                    ->withPivot('status', 'registered_at')
                    ->withTimestamps();
    }

    /* =========================================================
     | METHODS
     ==========================================================*/

    /**
     * ✅ Cek apakah user sudah terdaftar
     */
    public function isUserRegistered($userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    /**
     * ✅ Jumlah peserta terdaftar
     */
    public function getParticipantsCountAttribute(): int
    {
        return $this->participants()->count();
    }

    /* =========================================================
     | SCOPES
     ==========================================================*/

    /**
     * ✅ Perbaikan: Ganti orderLatest dengan method yang benar
     */
    public function scopeOrderByLatest($query)
    {
        return $query->orderByDesc('start_at')->orderByDesc('id');
    }

    // ✅ Atau buat scope terpisah untuk order
    public function scopeLatestFirst($query)
    {
        return $query->latest('start_at')->latest('id');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('location', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_at', '>', now());
    }

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where(function (Builder $w) {
            $now = now();
            $w->where(function (Builder $t) use ($now) {
                $t->whereNotNull('end_at')
                  ->where('start_at', '<=', $now)
                  ->where('end_at', '>=', $now);
            })->orWhere(function (Builder $t) use ($now) {
                $t->whereNull('end_at')
                  ->whereDate('start_at', $now->toDateString());
            });
        });
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where(function (Builder $w) {
            $now = now();
            $w->where(function (Builder $t) use ($now) {
                $t->whereNotNull('end_at')->where('end_at', '<', $now);
            })->orWhere(function (Builder $t) use ($now) {
                $t->whereNull('end_at')
                  ->whereDate('start_at', '<', $now->toDateString());
            });
        });
    }
}
