<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Users Management
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:student,admin',
        ]);

        $user->update($validated);

        return redirect('/admin/users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect('/admin/users')->with('success', 'User deleted successfully!');
    }

    public function generateUserToken($id)
    {
        $user = User::findOrFail($id);
        $token = $user->createToken('LibraryToken')->plainTextToken;

        return redirect('/admin/users')
            ->with('success', 'Token generated for ' . $user->email)
            ->with('generated_token', $token);
    }

    // Services Management
    public function services()
    {
        $services = Service::paginate(15);
        return view('admin.services', compact('services'));
    }

    // Show import form
    public function showImportForm()
    {
        return view('admin.import-services');
    }

    // Handle uploaded TXT or PDF and create Service entries
    public function importServices(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:txt,pdf|max:10240', // max 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        $created = 0;

        // If TXT, read lines and create services (expecting CSV-like lines: name|provider|duration_minutes|available_slots|description)
        if ($file->getClientOriginalExtension() === 'txt') {
            $content = file_get_contents(storage_path('app/public/' . $path));
            $lines = preg_split('/\r?\n/', $content);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                $parts = array_map('trim', explode('|', $line));
                $serviceData = [
                    'name' => $parts[0] ?? 'Service',
                    'provider' => $parts[1] ?? 'Staff',
                    'duration_minutes' => isset($parts[2]) ? (int)$parts[2] : 30,
                    'available_slots' => isset($parts[3]) ? (int)$parts[3] : 5,
                    'description' => $parts[4] ?? null,
                ];
                Service::create([
                    'name' => $parts[0] ?? 'Service',
                    'provider' => $parts[1] ?? 'Staff',
                    'duration_minutes' => isset($parts[2]) ? (int)$parts[2] : 30,
                    'available_slots' => isset($parts[3]) ? (int)$parts[3] : 5,
                    'description' => $parts[4] ?? null,
                ]);
                $created++;
            }
        } else {
            // For PDF: try to use Smalot\PdfParser if available to extract text, otherwise create a single service entry referencing the file
            $text = null;
            if (class_exists('\\Smalot\\PdfParser\\Parser')) {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile(storage_path('app/public/' . $path));
                    $text = $pdf->getText();
                } catch (\Exception $e) {
                    $text = null;
                }
            }

            if ($text) {
                // split by lines and try to create entries similar to TXT parsing
                $lines = preg_split('/\r?\n/', $text);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    $parts = array_map('trim', explode('|', $line));
                    $serviceData = [
                        'name' => $parts[0] ?? 'Service',
                        'provider' => $parts[1] ?? 'Staff',
                        'duration_minutes' => isset($parts[2]) ? (int)$parts[2] : 30,
                        'available_slots' => isset($parts[3]) ? (int)$parts[3] : 5,
                        'description' => $parts[4] ?? null,
                    ];
                    Service::create([
                        'name' => $parts[0] ?? 'Service',
                        'provider' => $parts[1] ?? 'Staff',
                        'duration_minutes' => isset($parts[2]) ? (int)$parts[2] : 30,
                        'available_slots' => isset($parts[3]) ? (int)$parts[3] : 5,
                        'description' => $parts[4] ?? null,
                    ]);
                    $created++;
                }
            } else {
                // Fallback: create a record using filename as title
                Service::create([
                    'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'provider' => 'Imported',
                    'duration_minutes' => 30,
                    'available_slots' => 5,
                    'description' => 'Imported file: ' . $file->getClientOriginalName(),
                ]);
                $created = 1;
            }
        }

        return redirect()->route('admin.services')->with('success', "Imported {$created} service(s).");
    }

    public function editService($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.edit-service', compact('service'));
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'available_slots' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update($validated);

        return redirect('/admin/services')->with('success', 'Service updated successfully!');
    }

    public function deleteService($id)
    {
        Service::findOrFail($id)->delete();
        return redirect('/admin/services')->with('success', 'Service deleted successfully!');
    }

    // Books Management
    public function books()
    {
        $books = Book::paginate(15);
        return view('admin.books', compact('books'));
    }

    // Dashboard
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalServices = Service::count();
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        
        // Library stats
        $totalBooks = Book::count();
        $totalBorrowRecords = BorrowRecord::count();
        $activeBorrows = BorrowRecord::where('status', 'borrowed')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalServices',
            'totalAppointments',
            'pendingAppointments',
            'totalBooks',
            'totalBorrowRecords',
            'activeBorrows'
        ));
    }

    // Appointment Records
    public function appointments()
    {
        $records = Appointment::with(['user', 'service', 'schedule'])->paginate(15);
        return view('admin.appointments', compact('records'));
    }

    public function approveAppointment($id)
    {
        $record = Appointment::findOrFail($id);
        $record->update(['status' => 'confirmed']);
        return back()->with('success', 'Appointment approved!');
    }

    public function rejectAppointment($id)
    {
        $record = Appointment::findOrFail($id);
        $record->update(['status' => 'rejected']);
        $record->service->increment('available_slots');
        return back()->with('success', 'Appointment rejected!');
    }
}
