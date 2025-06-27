<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PermissionController extends Controller
{
    public function index()
    {
        $query = SpatieRole::query();
        
        // Only superadmin can see the superadmin role
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('name', '!=', 'superadmin');
        }
        
        $roles = $query->get();
        return view('permissions.index', compact('roles'));
    }

    public function createAssign()
    {
        $employees = Employee::all();
        $roles = auth()->user()->hasRole('superadmin') 
            ? SpatieRole::all() 
            : SpatieRole::where('name', '!=', 'superadmin')->get();
        return view('permissions.assign', compact('employees', 'roles'));
    }

    public function assignPermission(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        
        // Prevent non-superadmins from assigning the superadmin role
        if (!auth()->user()->hasRole('superadmin')) {
            $roles = array_filter($request->input('roles', []), function ($roleId) {
                $role = SpatieRole::find($roleId);
                return $role && $role->name !== 'superadmin';
            });
            $employee->syncRoles($roles);
        } else {
            $employee->syncRoles($request->input('roles', []));
        }

        return redirect()->route('permissions.index')->with('success', 'Roles assigned successfully.');
    }

    public function edit($id)
    {
        $role = SpatieRole::findOrFail($id);

        // Prevent non-superadmins from editing superadmin role permissions
        if ($role->name === 'superadmin' && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to edit superadmin role permissions.');
        }

        $modules = $this->getModules();
        $actions = ['view', 'create', 'edit', 'delete'];
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('-', $permission->name)[1] ?? 'permissions';
        });

        return view('roles.permission', compact('role', 'modules', 'actions', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = SpatieRole::findOrFail($id);

        // Prevent non-superadmins from updating superadmin role permissions
        if ($role->name === 'superadmin' && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to update superadmin role permissions.');
        }

        $permissions = $request->input('permissions', []);

        // Sync permissions to role_has_permissions table
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Permissions updated successfully.');
    }

    protected function getModules()
    {
        $viewsPath = resource_path('views');
        $modules = [];

        $directories = File::directories($viewsPath);
        foreach ($directories as $directory) {
            $moduleName = basename($directory);
            if ($moduleName !== 'layouts' && $moduleName !== 'components' && $moduleName !== 'auth') {
                $modules[] = $moduleName;
            }
        }

        $modules = array_unique(array_merge($modules, ['permissions']));
        return $modules;
    }
}