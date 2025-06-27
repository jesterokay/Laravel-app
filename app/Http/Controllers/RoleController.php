<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
    public function index()
    {
        $query = SpatieRole::query();
        
        // Only superadmin can see the superadmin role
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('name', '!=', 'superadmin');
        }
        
        $roles = $query->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        SpatieRole::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show($id)
    {
        $role = SpatieRole::findOrFail($id);
        
        // Prevent non-superadmins from viewing superadmin role details
        if ($role->name === 'superadmin' && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to view superadmin role.');
        }
        
        return view('roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = SpatieRole::findOrFail($id);
        
        // Prevent non-superadmins from editing superadmin role
        if ($role->name === 'superadmin' && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to edit superadmin role.');
        }
        
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = SpatieRole::findOrFail($id);
        
        // Prevent non-superadmins from updating superadmin role
        if ($role->name === 'superadmin' && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to update superadmin role.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = SpatieRole::findOrFail($id);

        // Prevent deletion of superadmin role
        if ($role->name === 'superadmin') {
            return redirect()->route('roles.index')->with('error', 'Cannot delete the Super Admin role.');
        }

        // Prevent admin from deleting their own role
        if (auth()->user()->hasRole($role->name) && !auth()->user()->hasRole('superadmin')) {
            return redirect()->route('roles.index')->with('error', 'You cannot delete your own role.');
        }

        // Check if role has assigned users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Cannot delete role with assigned employees.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}