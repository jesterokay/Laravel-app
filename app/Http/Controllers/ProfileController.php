<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Make sure this is included

class ProfileController extends Controller
{
    public function edit()
    {
        $employee = auth()->user();
        return view('profile.edit', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = auth()->user();

        $request->validate([
            // 'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->update([
            // 'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('home')->with('success', 'Profile updated successfully. Looking good!');
    }
}