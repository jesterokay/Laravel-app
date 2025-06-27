<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonateController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware(function ($request, $next) {
    //         if (!Auth::user()->hasRole('superadmin')) {
    //             return redirect()->route('home')->with('error', 'Unauthorized access.');
    //         }
    //         return $next($request);
    //     })->only(['impersonate']);
    // }

    public function impersonate($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
    
        if ($employee->hasRole('superadmin')) {
            return redirect()->route('employees.index')->with('error', 'Cannot impersonate another superadmin.');
        }
    
        // Save the current superadmin ID so we can return later
        Session::put('impersonating', true);
        Session::put('original_user_id', Auth::id());
    
        // Now fully log in as the employee
        Auth::login($employee);
    
        return redirect()->route('home')->with('success', 'You are now impersonating ' . $employee->full_name);
    }
    
    public function stopImpersonating()
    {
        if (!Session::has('original_user_id')) {
            return redirect()->route('home')->with('error', 'You are not impersonating anyone.');
        }
    
        $originalUserId = Session::get('original_user_id');
        $originalUser = Employee::findOrFail($originalUserId);
    
        // Log back in as the original superadmin
        Auth::login($originalUser);
    
        // Clean up session
        Session::forget(['impersonating', 'original_user_id']);
    
        return redirect()->route('employees.index')->with('success', 'You have returned to your superadmin account.');
    }    
}