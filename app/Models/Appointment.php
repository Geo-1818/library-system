<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'schedule_id',
        'appointment_date',
        'status',
        'notes',
    ];

    protected $attributes = [
        'duration_exceeded' => false,
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'duration_exceeded' => 'boolean',
        'exceeded_notified_at' => 'datetime',
    ];

    public function getEndsAtAttribute()
    {
        if (! $this->appointment_date || ! $this->service) {
            return null;
        }

        return $this->appointment_date->copy()->addMinutes($this->service->duration_minutes ?? 0);
    }

    public function getIsDurationExceededAttribute()
    {
        if (! $this->ends_at) {
            return false;
        }

        return now()->greaterThan($this->ends_at);
    }

    public function getCanMarkCompletedAttribute()
    {
        return $this->status === 'confirmed'
            && $this->appointment_date
            && now()->greaterThanOrEqualTo($this->appointment_date);
    }

    public function markDurationExceededIfNeeded(): bool
    {
        if ($this->status !== 'confirmed' || $this->duration_exceeded) {
            return false;
        }

        if (! $this->appointment_date || ! $this->service) {
            return false;
        }

        $endsAt = $this->appointment_date->copy()->addMinutes($this->service->duration_minutes ?? 0);

        if (now()->greaterThanOrEqualTo($endsAt)) {
            $this->duration_exceeded = true;
            $this->exceeded_notified_at = now();
            $this->exceeded_message = 'You have exceeded the scheduled duration for this appointment. Please book another appointment to continue the session.';
            $this->save();

            return true;
        }

        return false;
    }

    public static function markDurationExceededForUser(int $userId): int
    {
        $count = 0;
        self::with('service')
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->where(function ($query) {
                $query->whereNull('duration_exceeded')->orWhere('duration_exceeded', false);
            })
            ->get()
            ->each(function ($appointment) use (&$count) {
                if ($appointment->markDurationExceededIfNeeded()) {
                    $count++;
                }
            });

        return $count;
    }

    public static function markDurationExceededForAll(): int
    {
        $count = 0;
        self::with('service')
            ->where('status', 'confirmed')
            ->where(function ($query) {
                $query->whereNull('duration_exceeded')->orWhere('duration_exceeded', false);
            })
            ->get()
            ->each(function ($appointment) use (&$count) {
                if ($appointment->markDurationExceededIfNeeded()) {
                    $count++;
                }
            });

        return $count;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
