<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $query = Employee::with(['department', 'position']);
        
        // Always exclude superadmin users
        $query->where('email', '!=', 'superjester@fake.com');
        
        $employees = $query->paginate(10);
        return response()->json($employees);
    }

    public function getDepartments()
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    public function getPositions()
    {
        $positions = Position::all();
        return response()->json($positions);
    }

    public function addUser(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'phone' => 'nullable|string',
            'username' => 'required|string|unique:employees,username',
            'password' => 'required|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|max:2048',
            'role' => 'nullable|in:admin,staff' // Restrict role to non-superadmin values
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    public function updateUser(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        if ($employee->role === 'superadmin' && (!Auth::check() || Auth::user()->role !== 'superadmin')) {
            return response()->json(['message' => 'Unauthorized to update superadmin user'], 403);
        }

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'phone' => 'nullable|string',
            'username' => 'required|string|unique:employees,username,' . $id,
            'password' => 'nullable|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $id,
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|max:2048',
            'role' => 'nullable|in:admin,staff' // Restrict role to non-superadmin values
        ]);

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return response()->json([
            'message' => 'Updated successfully',
            'user' => $employee->load(['department', 'position'])
        ]);
    }

    public function deleteUser($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        if ($employee->role === 'superadmin') {
            return response()->json(['message' => 'Cannot delete superadmin user'], 403);
        }

        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }

        $employee->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}