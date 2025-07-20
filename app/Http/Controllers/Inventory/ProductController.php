<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        $products->getCollection()->transform(function ($product) {
            $product->imageUrl = $this->getTelegramImageUrl($product->image);
            return $product;
        });
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'image' => 'required|image|mimes:jpg,png,gif|max:51200',
        ]);

        $client = new Client();
        $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
        $chatId = '-1002710137316';
        $messageThreadId = 27;

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
                return redirect()->back()->with('error', 'Failed to upload image to Telegram.');
            }
            $validated['image'] = $data['result']['photo'][0]['file_id'];
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Failed to upload image to Telegram.');
        }

        DB::beginTransaction();
        try {
            Product::create($validated);
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create product.');
        }
    }

    public function show(Product $product)
    {
        $product->load('category');
        $imageUrl = $this->getTelegramImageUrl($product->image);
        return view('products.show', compact('product', 'imageUrl'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $imageUrl = $this->getTelegramImageUrl($product->image);
        return view('products.edit', compact('product', 'categories', 'imageUrl'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:51200',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $client = new Client();
                $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
                $chatId = '-1002710137316';
                $messageThreadId = 27;

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
                    return redirect()->back()->with('error', 'Failed to upload image to Telegram.');
                }
                $validated['image'] = $data['result']['photo'][0]['file_id'];
            }

            $product->update($validated);
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update product.');
        }
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->hasRole('superadmin') && !auth()->user()->hasPermissionTo('delete-products')) {
            abort(403, 'Unauthorized action.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
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
            }
        } catch (RequestException $e) {
            // Log the error
        }

        return null;
    }
}
