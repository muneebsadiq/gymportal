<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $query = Coach::query();
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('phone', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('email', 'LIKE', '%'.$request->search.'%');
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $coaches = $query->latest()->paginate(20);
        return view('coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('coaches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|unique:coaches,email',
            'specialization' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        Coach::create($validated);
        return redirect()->route('coaches.index')->with('success', 'Coach created successfully!');
    }

    public function show(Coach $coach)
    {
        return view('coaches.show', compact('coach'));
    }

    public function edit(Coach $coach)
    {
        return view('coaches.edit', compact('coach'));
    }

    public function update(Request $request, Coach $coach)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|unique:coaches,email,'.$coach->id,
            'specialization' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $coach->update($validated);
        return redirect()->route('coaches.index')->with('success', 'Coach updated successfully!');
    }

    public function destroy(Coach $coach)
    {
        $coach->delete();
        return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully!');
    }
}


