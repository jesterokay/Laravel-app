@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold text-gray-700">Modules</h1>
        <a href="{{ route('modulemanagement.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create New Module</a>
    </div>
    <div class="bg-white shadow-md rounded-lg">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $module)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $module->name }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $module->description }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $module->enabled ? 'text-green-900' : 'text-red-900' }}">
                            <span aria-hidden class="absolute inset-0 {{ $module->enabled ? 'bg-green-200' : 'bg-red-200' }} opacity-50 rounded-full"></span>
                            <span class="relative">{{ $module->enabled ? 'Enabled' : 'Disabled' }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        @php
                            $routeName = strtolower($module->name) . '.index';
                        @endphp
                        @if (Route::has($routeName))
                            <a href="{{ route($routeName) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View</a>
                        @else
                            <span class="text-gray-400">View</span>
                        @endif
                        <form action="{{ route('modulemanagement.toggle', $module->id) }}" method="POST" class="inline-block ml-4">
                            @csrf
                            <button type="submit" class="text-sm {{ $module->enabled ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                {{ $module->enabled ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <a href="{{ route('modulemanagement.edit', $module->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                        <form action="{{ route('modulemanagement.destroy', $module->id) }}" method="POST" class="inline-block ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
