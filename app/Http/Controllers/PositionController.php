<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-positions')) {
            throw UnauthorizedException::forPermissions(['view-positions']);
        }

        $positions = Position::latest()->paginate(10);
        return view('positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-positions')) {
            throw UnauthorizedException::forPermissions(['create-positions']);
        }

        return view('positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-positions')) {
            throw UnauthorizedException::forPermissions(['create-positions']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions,name',
            'description' => 'nullable|string',
        ]);

        Position::create($validated);

        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        if (!auth()->user()->hasPermissionTo('view-positions')) {
            throw UnauthorizedException::forPermissions(['view-positions']);
        }

        return view('positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        if (!auth()->user()->hasPermissionTo('edit-positions')) {
            throw UnauthorizedException::forPermissions(['edit-positions']);
        }

        return view('positions.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        if (!auth()->user()->hasPermissionTo('edit-positions')) {
            throw UnauthorizedException::forPermissions(['edit-positions']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
            'description' => 'nullable|string',
        ]);

        $position->update($validated);

        return redirect()->route('positions.index')->with('success', 'Position updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        if (!auth()->user()->hasPermissionTo('delete-positions')) {
            throw UnauthorizedException::forPermissions(['delete-positions']);
        }

        if ($position->employees()->count() > 0) {
            return redirect()->route('positions.index')->with('error', 'Cannot delete position with assigned employees.');
        }

        if ($position->name === 'Super Admin') {
            return redirect()->route('positions.index')->with('error', 'Cannot delete the Super Admin position.');
        }

        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
    }
}