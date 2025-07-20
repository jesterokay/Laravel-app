<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $query->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'superadmin');
            });
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
            'image' => 'required|image|mimes:jpg,png,gif|max:51200',
        ]);

        $client = new Client();
        $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
        $chatId = '-1002710137316';
        $messageThreadId = 8;

        try {
            $response = $client->post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
                'multipart' => [
                    [
                        'name' => 'chat_id',
                        'contents' => $chatId,
                    ],
                    [
                        'name' => 'message_thread_id',
                        'contents' => $messageThreadId,
                    ],
                    [
                        'name' => 'photo',
                        'contents' => fopen($request->file('image')->getRealPath(), 'r'),
                        'filename' => $request->file('image')->getClientOriginalName(),
                    ],
                ],
                'timeout' => 30,
            ]);
            $data = json_decode($response->getBody(), true);
            if (!$data['ok']) {
                ([
                    'response' => $data,
                    'error_code' => $data['error_code'] ?? 'N/A',
                    'error_message' => $data['description'] ?? 'Unknown error',
                ]);
                return redirect()->back()->with('error', 'Failed to upload image to Telegram.');
            }
            $validated['image'] = $data['result']['photo'][count($data['result']['photo']) - 1]['file_id'];
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Failed to upload image to Telegram.');
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
            return redirect()->back()->with('error', 'Failed to create employee.');
        }
    }

    public function show(Employee $employee)
    {
        if ($employee->hasRole('superadmin') && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to view superadmin.');
        }

        $imageUrl = $this->getTelegramImageUrl($employee->image);

        return view('employees.show', compact('employee', 'imageUrl'));
    }

    public function edit(Employee $employee)
    {
        if ($employee->hasRole('superadmin') && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to edit superadmin.');
        }
        $departments = Department::all();
        $positions = Position::all();
        $spatieRoles = SpatieRole::where('name', '!=', 'superadmin')->get();
        return view('employees.edit', compact('employee', 'departments', 'positions', 'spatieRoles'));
    }

    public function update(Request $request, Employee $employee)
    {
        if ($employee->hasRole('superadmin') && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to update superadmin.');
        }

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'spatie_role' => [
                'required',
                'exists:roles,name',
                $employee->hasRole('superadmin') ? 'in:superadmin' : 'not_in:superadmin',
            ],
            'username' => 'required|unique:employees,username,' . $employee->id,
            'password' => 'nullable|confirmed|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|mimes:jpg,png,gif|max:51200',
        ]);

        DB::beginTransaction();
        try {
            $validated['image'] = $request->hasFile('image') ? null : $employee->image;

            if ($request->hasFile('image')) {
                $client = new Client();
                $response = $client->post("https://api.telegram.org/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/sendPhoto", [
                    'multipart' => [
                        [
                            'name' => 'chat_id',
                            'contents' => '-1002710137316',
                        ],
                        [
                            'name' => 'message_thread_id',
                            'contents' => '8',
                        ],
                        [
                            'name' => 'photo',
                            'contents' => fopen($request->file('image')->getRealPath(), 'r'),
                            'filename' => $request->file('image')->getClientOriginalName(),
                        ],
                    ],
                    'timeout' => 30,
                ]);
                $data = json_decode($response->getBody(), true);
                if (!$data['ok']) {
                    ([
                        'response' => $data,
                        'error_code' => $data['error_code'] ?? 'N/A',
                        'error_message' => $data['description'] ?? 'Unknown error',
                    ]);
                    return redirect()->back()->with('error', 'Failed to upload image to Telegram.');
                }
                $validated['image'] = $data['result']['photo'][count($data['result']['photo']) - 1]['file_id'];
            }

            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            if ($employee->hasRole('superadmin')) {
                $validated['spatie_role'] = 'superadmin';
            }

            $employee->update($validated);
            $employee->syncRoles($validated['spatie_role']);
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update employee.');
        }
    }

    public function destroy(Employee $employee)
    {
        if ($employee->hasRole('superadmin')) {
            abort(403, 'Unauthorized to delete superadmin.');
        }
        try {
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete employee.');
        }
    }

    protected function getTelegramImageUrl($fileId)
    {
        if (!$fileId) {
            return null;
        }

        $client = new Client();
        $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';

        try {
            $response = $client->get("https://api.telegram.org/bot{$botToken}/getFile", [
                'query' => ['file_id' => $fileId],
                'timeout' => 10,
            ]);
            $data = json_decode($response->getBody(), true);
            if ($data['ok']) {
                $filePath = $data['result']['file_path'];
                return "https://api.telegram.org/file/bot{$botToken}/{$filePath}";
            } else {
                ([
                    'file_id' => $fileId,
                    'response' => $data,
                ]);
            }
        } catch (RequestException $e) {
            ([
                'file_id' => $fileId,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function getUserImageUrl()
    {
        $user = Auth::user();
        $imageUrl = $this->getTelegramImageUrl($user->image);
        return response()->json(['imageUrl' => $imageUrl]);
    }
}