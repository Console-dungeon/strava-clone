<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = auth()->user()
            ->activities()
            ->latest()
            ->get();

        return Inertia::render('Activities/Index', [
            'activities' => $activities
        ]);
    }

    public function create(){
        return Inertia::render('Activities/Create');
    }

    public function main(){
        return Inertia::render('Activities/Main');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'distance' => 'required|numeric',
            'duration' => 'required|integer',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->activities()->create($data);

        return redirect()->route('dashboard');
    }
}