<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Service;use App\Models\Schedule;use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    
    public function run(): void
    {
        
        User::updateOrCreate(
            ['email' => 'admin@booking.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('Admin@123'),
                'role' => 'admin',
            ]
        );

        
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]
        );

        
        Book::updateOrCreate(
            ['isbn' => '9780143127741'],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'quantity' => 5,
                'description' => 'Explores the history and impact of Homo sapiens.',
            ]
        );

        Book::updateOrCreate(
            ['isbn' => '9780307277785'],
            [
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'quantity' => 4,
                'description' => 'A tour of the mind and the two systems that drive the way we think.',
            ]
        );

        Book::updateOrCreate(
            ['isbn' => '9780062316097'],
            [
                'title' => 'The Alchemist',
                'author' => 'Paulo Coelho',
                'quantity' => 6,
                'description' => 'A fable about following your dream.',
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Book Borrowing'],
            [
                'provider' => 'Library Desk',
                'duration_minutes' => 30,
                'available_slots' => 10,
                'description' => 'Reserve time to borrow books and get help from library staff.',
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Study Room Reservation'],
            [
                'provider' => 'Library Facilities',
                'duration_minutes' => 120,
                'available_slots' => 5,
                'description' => 'Reserve a study room for group or solo study sessions.',
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Research Consultation'],
            [
                'provider' => 'Research Librarian',
                'duration_minutes' => 45,
                'available_slots' => 8,
                'description' => 'Book a consultation for research assistance and reference support.',
            ]
        );

        $bookBorrowing = Service::where('name', 'Book Borrowing')->first();
        if ($bookBorrowing) {
            Schedule::updateOrCreate([
                'service_id' => $bookBorrowing->id,
                'available_date' => '2026-06-15',
                'start_time' => '09:00:00',
                'end_time' => '09:30:00',
            ], [
                'available_slots' => 3,
            ]);

            Schedule::updateOrCreate([
                'service_id' => $bookBorrowing->id,
                'available_date' => '2026-06-15',
                'start_time' => '10:00:00',
                'end_time' => '10:30:00',
            ], [
                'available_slots' => 3,
            ]);
        }

        $studyRoom = Service::where('name', 'Study Room Reservation')->first();
        if ($studyRoom) {
            Schedule::updateOrCreate([
                'service_id' => $studyRoom->id,
                'available_date' => '2026-06-16',
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
            ], [
                'available_slots' => 2,
            ]);
        }

        $researchConsultation = Service::where('name', 'Research Consultation')->first();
        if ($researchConsultation) {
            Schedule::updateOrCreate([
                'service_id' => $researchConsultation->id,
                'available_date' => '2026-06-16',
                'start_time' => '15:30:00',
                'end_time' => '16:15:00',
            ], [
                'available_slots' => 4,
            ]);
        }
    }
}
