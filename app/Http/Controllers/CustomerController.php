<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-customers')) {
            throw UnauthorizedException::forPermissions(['create-customers']);
        }

        return view('customers.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-customers')) {
            throw UnauthorizedException::forPermissions(['create-customers']);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,prospect',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
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
                Log::error('ImgBB upload failed for customer', [
                    'response' => $data,
                    'error_code' => $data['error']['code'] ?? 'N/A',
                    'error_message' => $data['error']['message'] ?? 'Unknown error',
                ]);
                return redirect()->back()->with('error', 'Failed to upload image: ' . ($data['error']['message'] ?? 'Unknown error'));
            }
            $validated['image'] = $data['data']['url'];
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('ImgBB upload error for customer', ['error' => $errorMessage]);
            return redirect()->back()->with('error', 'Image upload failed: ' . $errorMessage);
        }

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('view-customers')) {
            throw UnauthorizedException::forPermissions(['view-customers']);
        }

        $customer->load(['leads', 'contacts', 'tasks', 'followUps']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('edit-customers')) {
            throw UnauthorizedException::forPermissions(['edit-customers']);
        }

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('edit-customers')) {
            throw UnauthorizedException::forPermissions(['edit-customers']);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,prospect',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
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
                    Log::error('ImgBB upload failed for customer update', [
                        'response' => $data,
                        'error_code' => $data['error']['code'] ?? 'N/A',
                        'error_message' => $data['error']['message'] ?? 'Unknown error',
                    ]);
                    return redirect()->back()->with('error', 'Failed to upload image: ' . ($data['error']['message'] ?? 'Unknown error'));
                }
                $validated['image'] = $data['data']['url'];
            } catch (RequestException $e) {
                $errorMessage = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
                Log::error('ImgBB upload error for customer update', ['error' => $errorMessage]);
                return redirect()->back()->with('error', 'Image upload failed: ' . $errorMessage);
            }
        }

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('delete-customers')) {
            throw UnauthorizedException::forPermissions(['delete-customers']);
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}