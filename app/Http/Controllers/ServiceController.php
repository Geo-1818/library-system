<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::with(['schedules' => function ($query) {
            $query->where('available_slots', '>', 0)
                ->orderBy('available_date')
                ->orderBy('start_time');
        }])->findOrFail($id);

        return view('services.book', compact('service'));
    }
}
