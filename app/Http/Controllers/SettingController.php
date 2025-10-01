<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::get();
        return view('settings.index', compact('settings'));
    }

    public function edit()
    {
        $settings = Setting::get();
        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'gym_name' => 'required|string|max:255',
            'gym_email' => 'nullable|email|max:255',
            'gym_phone' => 'nullable|string|max:30',
            'gym_address' => 'nullable|string|max:500',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:max_width=400,max_height=400',
            'about' => 'nullable|string|max:1000',
        ]);

        $settings = Setting::get();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Validate dimensions manually for better error message
            $image = getimagesize($request->file('logo')->getRealPath());
            if ($image[0] > 400 || $image[1] > 400) {
                return back()->withErrors(['logo' => 'Logo dimensions must not exceed 400x400 pixels. Current size: ' . $image[0] . 'x' . $image[1]])->withInput();
            }
            
            // Delete old logo if exists
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Ensure working_days is set
        $validated['working_days'] = $request->input('working_days', []);

        $settings->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
