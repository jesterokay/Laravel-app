<?php

namespace App\Http\Controllers;

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

    // Show the attendance toggle page
    public function showTogglePage()
    {
        $employee_id = auth()->user()->id; // Assumes employees are authenticated users
        
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee_id)
            ->where('date', $today)
            ->first();

        $isChecked = $attendance && $attendance->check_in && !$attendance->check_out;

        // Generate QR code if not checked in
        $qrCode = '';
        if (!$isChecked) {
            $token = Str::random(32);
            \Cache::put("qrcode_token_{$employee_id}", $token, now()->endOfDay());

            $checkInUrl = route('attendances.scan-checkin', [
                'employee_id' => $employee_id,
                'is_checked' => true,
                'token' => $token
            ]);

            // Use GoQR.me API to generate QR code
            $response = Http::get('https://api.qrserver.com/v1/create-qr-code/', [
                'data' => $checkInUrl,
                'size' => '200x200',
                'format' => 'png'
            ]);

            if ($response->successful()) {
                $qrCode = $response->effectiveUri(); // Get the URL of the generated QR code
            }
        }

        return view('attendances.toggle', compact('employee_id', 'isChecked', 'qrCode'));
    }

    // Handle Check In / Check Out
    public function toggleCheckInOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'is_checked' => 'required|boolean',
        ]);

        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', $today)
            ->first();

        if ($request->is_checked) {
            if (!$attendance) {
                Attendance::create([
                    'employee_id' => $request->employee_id,
                    'date' => $today,
                    'check_in' => now(),
                    'status' => 'present'
                ]);
                return response()->json(['message' => 'Checked in successfully. Time to shine!']);
            }
            return response()->json(['error' => 'Already checked in. No double-dipping!'], 400);
        } else {
            if ($attendance && !$attendance->check_out) {
                $attendance->update([
                    'check_out' => now(),
                    'status' => $this->calculateAttendanceStatus($attendance->check_in)
                ]);
                return response()->json(['message' => 'Checked out successfully. Catch you later!']);
            }
            return response()->json(['error' => 'Not checked in or already checked out. What\'s up?'], 400);
        }
    }

    // Handle QR code scan for check-in
    public function scanCheckIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'is_checked' => 'required|boolean',
            'token' => 'required|string'
        ]);

        $employee_id = $request->employee_id;
        $is_checked = $request->is_checked;
        $token = $request->token;

        // Verify the token
        $cachedToken = \Cache::get("qrcode_token_{$employee_id}");
        if (!$cachedToken || $cachedToken !== $token) {
            return response()->json(['error' => 'Invalid or expired QR code.'], 400);
        }

        // Invalidate the token after use
        \Cache::forget("qrcode_token_{$employee_id}");

        // Simulate the toggleCheckInOut action
        return $this->toggleCheckInOut(new Request([
            'employee_id' => $employee_id,
            'is_checked' => $is_checked
        ]));
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
}