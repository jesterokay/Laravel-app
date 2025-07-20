<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-customers')) {
            throw UnauthorizedException::forPermissions(['view-customers']);
        }

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

        $client = new Client();
        $botToken = env('TELEGRAM_BOT_TOKEN', '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE');
        $chatId = env('TELEGRAM_CHAT_ID', '-1002710137316');
        $messageThreadId = env('TELEGRAM_CUSTOMER_TOPIC_ID', 5);

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
            if (!$data['ok']) {([
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

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('view-customers')) {
            throw UnauthorizedException::forPermissions(['view-customers']);
        }

        $imageUrl = $this->getTelegramImageUrl($customer->image);

        $customer->load(['leads', 'contacts', 'tasks', 'followUps']);
        return view('customers.show', compact('customer', 'imageUrl'));
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

        $client = new Client();
        $botToken = env('TELEGRAM_BOT_TOKEN', '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE');
        $chatId = env('TELEGRAM_CHAT_ID', '-1002710137316');
        $messageThreadId = env('TELEGRAM_CUSTOMER_TOPIC_ID', 5);

        if ($request->hasFile('image')) {
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
                if (!$data['ok']) {([
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
        } else {
            $validated['image'] = $customer->image;
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

    protected function getTelegramImageUrl($fileId)
    {
        if (!$fileId) {
            return null;
        }

        $client = new Client();
        $botToken = env('TELEGRAM_BOT_TOKEN', '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE');

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
}