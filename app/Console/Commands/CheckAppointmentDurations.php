<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class CheckAppointmentDurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:check-durations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks confirmed appointments and marks duration-exceeded notifications.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking appointment durations...');

        $appointments = Appointment::with('service')
            ->where('status', 'confirmed')
            ->where(function ($q) {
                $q->whereNull('duration_exceeded')->orWhere('duration_exceeded', false);
            })
            ->get();

        $now = Carbon::now();

        $count = 0;
        foreach ($appointments as $appointment) {
            if (! $appointment->appointment_date || ! $appointment->service) {
                continue;
            }

            $endsAt = $appointment->appointment_date->copy()->addMinutes($appointment->service->duration_minutes ?? 0);

            if ($now->greaterThanOrEqualTo($endsAt)) {
                $appointment->duration_exceeded = true;
                $appointment->exceeded_notified_at = $now;
                $appointment->exceeded_message = 'The appointment has reached its scheduled duration. Please book another appointment to continue the session.';
                $appointment->save();
                $count++;
            }
        }

        $this->info("Marked {$count} appointments as duration-exceeded.");

        return 0;
    }
}
