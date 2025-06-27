<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-departments')) {
            throw UnauthorizedException::forPermissions(['view-departments']);
        }

        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-departments')) {
            throw UnauthorizedException::forPermissions(['create-departments']);
        }

        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-departments')) {
            throw UnauthorizedException::forPermissions(['create-departments']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        if (!auth()->user()->hasPermissionTo('view-departments')) {
            throw UnauthorizedException::forPermissions(['view-departments']);
        }

        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        if (!auth()->user()->hasPermissionTo('edit-departments')) {
            throw UnauthorizedException::forPermissions(['edit-departments']);
        }

        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        if (!auth()->user()->hasPermissionTo('edit-departments')) {
            throw UnauthorizedException::forPermissions(['edit-departments']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        if (!auth()->user()->hasPermissionTo('delete-departments')) {
            throw UnauthorizedException::forPermissions(['delete-departments']);
        }

        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete department with assigned employees.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}