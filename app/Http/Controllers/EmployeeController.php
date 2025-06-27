<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class EmployeeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-employees')) {
            abort(403, 'Unauthorized to view employee');
        }

        $query = Employee::with(['department', 'position'])->latest();

        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('id', '!=', 1);
        }

        $employees = $query->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $spatieRoles = SpatieRole::where('name', '!=', 'superadmin')->get();
        return view('employees.create', compact('departments', 'positions', 'spatieRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'spatie_role' => 'required|exists:roles,name|not_in:superadmin',
            'username' => 'required|unique:employees,username',
            'password' => 'required|confirmed|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'required|image|max:2048',
        ]);

        try {
            $client = new Client();
            $response = $client->post('https://api.imgbb.com/1/upload', [
                'multipart' => [
                    [
                        'name' => 'key',
                        'contents' => env('IMGBB_API_KEY'),
                    ],
                    [
                        'name' => 'image',
                        'contents' => fopen($request->file('image')->getRealPath(), 'r'),
                    ],
                ],
                'timeout' => 30,
            ]);
            $data = json_decode($response->getBody(), true);
            if (!$data['success']) {
                Log::error('ImgBB upload failed for employee', [
                    'response' => $data,
                    'error_code' => $data['error']['code'] ?? 'N/A',
                    'error_message' => $data['error']['message'] ?? 'Unknown error',
                ]);
                return redirect()->back()->with('error', 'Failed to upload image: ' . ($data['error']['message'] ?? 'Unknown error'));
            }
            $validated['image'] = $data['data']['url'];
            Log::info('Image uploaded to ImgBB', ['url' => $validated['image']]);
        } catch (RequestException $e) {
            Log::error('ImgBB upload error', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to upload image to ImgBB.');
        }

        $validated['password'] = Hash::make($validated['password']);
        DB::beginTransaction();
        try {
            $employee = Employee::create($validated);
            $employee->assignRole($validated['spatie_role']);
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create employee.');
        }
    }

    public function show(Employee $employee)
    {
        if ($employee->id === 1 && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to view superadmin.');
        }
        Log::info('Displaying employee image', ['employee_id' => $employee->id, 'image_url' => $employee->image_url]);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        if ($employee->id === 1 && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to edit superadmin.');
        }
        $departments = Department::all();
        $positions = Position::all();
        $spatieRoles = SpatieRole::where('name', '!=', 'superadmin')->get();
        return view('employees.edit', compact('employee', 'departments', 'positions', 'spatieRoles'));
    }

    public function update(Request $request, Employee $employee)
    {
        if ($employee->id === 1 && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to update superadmin.');
        }

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'spatie_role' => 'required|exists:roles,name|not_in:superadmin',
            'username' => 'required|unique:employees,username,' . $employee->id,
            'password' => 'nullable|confirmed|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Preserve existing image URL if no new image is uploaded
            $validated['image'] = $request->hasFile('image') ? null : $employee->image;

            if ($request->hasFile('image')) {
                $client = new Client();
                $response = $client->post('https://api.imgbb.com/1/upload', [
                    'multipart' => [
                        [
                            'name' => 'key',
                            'contents' => env('IMGBB_API_KEY'),
                        ],
                        [
                            'name' => 'image',
                            'contents' => fopen($request->file('image')->getRealPath(), 'r'),
                        ],
                    ],
                    'timeout' => 30,
                ]);
                $data = json_decode($response->getBody(), true);
                if (!$data['success']) {
                    Log::error('ImgBB upload failed for employee update', [
                        'response' => $data,
                        'error_code' => $data['error']['code'] ?? 'N/A',
                        'error_message' => $data['error']['message'] ?? 'Unknown error',
                    ]);
                    return redirect()->back()->with('error', 'Failed to upload image: ' . ($data['error']['message'] ?? 'Unknown error'));
                }
                $validated['image'] = $data['data']['url'];
                Log::info('Image uploaded to ImgBB for update', ['url' => $validated['image']]);
            }

            if (empty($validated['password'])) {
                unset($validated['password']); // Don't update password if not provided
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            $employee->update($validated);
            $employee->syncRoles($validated['spatie_role']);
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update employee.');
        }
    }

    public function destroy(Employee $employee)
    {
        if ($employee->id === 1) {
            abort(403, 'Unauthorized to delete superadmin.');
        }
        try {
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Employee deletion failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete employee.');
        }
    }
}