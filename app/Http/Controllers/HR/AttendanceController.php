<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        // Build query for the authenticated user's attendance records only
        $query = Attendance::with('employee')->where('employee_id', auth()->user()->id);

        // Paginate results
        $attendances = $query->paginate(20);

        // Get the authenticated employee for display (optional dropdown or name)
        $employees = Employee::where('id', auth()->user()->id)->get();

        return view('attendances.index', compact('attendances', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
        ]);

        $date = Carbon::parse($validatedData['date']);

        if ($validatedData['check_in']) {
            $checkIn = Carbon::createFromTimeString($validatedData['check_in'])->setDateFrom($date);
            $validatedData['check_in'] = $checkIn;
        }

        if ($validatedData['check_out']) {
            $checkOut = Carbon::createFromTimeString($validatedData['check_out'])->setDateFrom($date);
            $validatedData['check_out'] = $checkOut;
        }

        Attendance::create($validatedData);

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record created successfully.');
    }

    public function show($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        return view('attendances.show', compact('attendance'));
    }

    // Edit attendance record
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $employees = Employee::all();
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    // Update attendance record
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'nullable|in:present,absent,late,half-day',
            'notes' => 'nullable|string|max:500'
        ]);

        // Convert time inputs to full datetime
        $date = Carbon::parse($validatedData['date']);
        
        if ($validatedData['check_in']) {
            $checkIn = Carbon::createFromTimeString($validatedData['check_in'])->setDateFrom($date);
            $validatedData['check_in'] = $checkIn;
        }

        if ($validatedData['check_out']) {
            $checkOut = Carbon::createFromTimeString($validatedData['check_out'])->setDateFrom($date);
            $validatedData['check_out'] = $checkOut;
        }

        $attendance->update($validatedData);

        return redirect()->route('attendances.show', $attendance->id)
            ->with('success', 'Attendance record updated successfully.');
    }

    // Handle Check In / Check Out
    public function toggle(Request $request)
    {
        $employee_id = auth()->user()->id;
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee_id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            Attendance::create([
                'employee_id' => $employee_id,
                'date' => $today,
                'check_in' => now(),
                'status' => 'present'
            ]);
            return response()->json(['message' => 'Checked in successfully. Time to shine!']);
        }

        if ($attendance && !$attendance->check_out) {
            $attendance->update([
                'check_out' => now(),
                'status' => $this->calculateAttendanceStatus($attendance->check_in)
            ]);
            return response()->json(['message' => 'Checked out successfully. Catch you later!']);
        }

        return response()->json(['error' => 'Already checked out for the day.'], 400);
    }

    // Calculate attendance status based on check-in time
    private function calculateAttendanceStatus($checkIn)
    {
        $checkInTime = Carbon::parse($checkIn);
        $standardStartTime = Carbon::parse('09:00:00');

        // If checked in after 9:30 AM, mark as late
        if ($checkInTime->gt($standardStartTime->copy()->addMinutes(30))) {
            return 'late';
        }

        return 'present';
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record deleted successfully.');
    }
}
