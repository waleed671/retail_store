<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display the main Point of Sale (POS) counter interface.
     */
    public function index()
    {
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->orderBy('name')->get();

        // Load all active products for instantaneous client-side search, barcode scan, and category filtering
        $products = Product::where('is_active', true)
            ->with('category:id,name')
            ->select('id', 'category_id', 'name', 'sku', 'price', 'discount_price', 'stock', 'image')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id'             => $product->id,
                    'name'           => $product->name,
                    'sku'            => $product->sku,
                    'price'          => (float) $product->price,
                    'final_price'    => (float) ($product->discount_price ?? $product->price),
                    'discount_price' => $product->discount_price ? (float) $product->discount_price : null,
                    'stock'          => (int) $product->stock,
                    'category_id'    => $product->category_id,
                    'category_name'  => $product->category?->name ?? 'General',
                    'image'          => $product->image ? asset('storage/' . $product->image) : null,
                ];
            });

        $customers = User::where('role', 'customer')
            ->select('id', 'name', 'phone', 'email')
            ->orderBy('name')
            ->take(200)
            ->get();

        $today = now()->startOfDay();

        $todayStats = [
            'orders_count' => Order::where('source', 'pos')->where('created_at', '>=', $today)->count(),
            'total_sales'  => (float) Order::where('source', 'pos')->where('created_at', '>=', $today)->sum('total'),
            'cash_sales'   => (float) Order::where('source', 'pos')->where('created_at', '>=', $today)->where('payment_method', 'cash')->sum('total'),
            'digital_sales'=> (float) Order::where('source', 'pos')->where('created_at', '>=', $today)->whereIn('payment_method', ['jazzcash', 'easypaisa', 'card', 'bank_transfer'])->sum('total'),
        ];

        return view('admin.pos.index', compact('categories', 'products', 'customers', 'todayStats'));
    }

    /**
     * Live search endpoint (useful for large catalogs or barcode scanner queries).
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
            })
            ->with('category:id,name')
            ->select('id', 'category_id', 'name', 'sku', 'price', 'discount_price', 'stock', 'image')
            ->take(20)
            ->get()
            ->map(function ($product) {
                return [
                    'id'             => $product->id,
                    'name'           => $product->name,
                    'sku'            => $product->sku,
                    'price'          => (float) $product->price,
                    'final_price'    => (float) ($product->discount_price ?? $product->price),
                    'stock'          => (int) $product->stock,
                    'category_name'  => $product->category?->name ?? 'General',
                    'image'          => $product->image ? asset('storage/' . $product->image) : null,
                ];
            });

        return response()->json($products);
    }

    /**
     * Complete an on-the-spot POS sale.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'             => ['required', 'array', 'min:1'],
            'items.*.id'        => ['required', 'exists:products,id'],
            'items.*.quantity'  => ['required', 'integer', 'min:1'],
            'payment_method'    => ['required', 'string', 'in:cash,jazzcash,easypaisa,card,bank_transfer'],
            'paid_amount'       => ['required', 'numeric', 'min:0'],
            'discount_amount'   => ['nullable', 'numeric', 'min:0'],
            'customer_name'     => ['nullable', 'string', 'max:150'],
            'customer_phone'    => ['nullable', 'string', 'max:30'],
            'customer_id'       => ['nullable', 'exists:users,id'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'notes'             => ['nullable', 'string', 'max:500'],
        ]);

        $itemInputs = collect($request->items);
        $productIds = $itemInputs->pluck('id')->all();

        try {
            $order = DB::transaction(function () use ($request, $itemInputs, $productIds) {
                // Lock and retrieve products to avoid race conditions
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $subtotal = 0;
                $lineItemsData = [];

                foreach ($itemInputs as $item) {
                    $prod = $products->get($item['id']);
                    if (! $prod) {
                        throw new \Exception("Product not found (ID: {$item['id']}).");
                    }

                    $qty = (int) $item['quantity'];
                    if ($prod->stock < $qty) {
                        throw new \Exception("Insufficient stock for '{$prod->name}'. Available: {$prod->stock}, Requested: {$qty}.");
                    }

                    $unitPrice = (float) ($prod->discount_price ?? $prod->price);
                    $lineTotal = $unitPrice * $qty;
                    $subtotal += $lineTotal;

                    $lineItemsData[] = [
                        'product'    => $prod,
                        'unit_price' => $unitPrice,
                        'quantity'   => $qty,
                        'subtotal'   => $lineTotal,
                    ];
                }

                $discountAmount = min($subtotal, (float) ($request->discount_amount ?? 0));
                $grandTotal = max(0, $subtotal - $discountAmount);
                $paidAmount = (float) $request->paid_amount;
                $changeAmount = max(0, $paidAmount - $grandTotal);

                // Customer info
                $customerName = trim($request->customer_name ?: '');
                $customerPhone = trim($request->customer_phone ?: '');
                if ($customerName === '') {
                    $customerName = 'Walk-in Customer';
                }

                $orderNumber = Order::generateOrderNumber();

                $order = Order::create([
                    'source'            => 'pos',
                    'user_id'           => $request->customer_id ?: null,
                    'cashier_id'        => Auth::id(),
                    'order_number'      => $orderNumber,
                    'status'            => 'delivered', // fulfilled instantly at counter
                    'payment_method'    => $request->payment_method,
                    'payment_status'    => 'paid',
                    'subtotal'          => $subtotal,
                    'shipping_fee'      => 0,
                    'discount_amount'   => $discountAmount,
                    'total'             => $grandTotal,
                    'paid_amount'       => $paidAmount,
                    'change_amount'     => $changeAmount,
                    'payment_reference' => $request->payment_reference,
                    'customer_name'     => $customerName,
                    'customer_phone'    => $customerPhone ?: 'Counter Sale',
                    'customer_email'    => null,
                    'shipping_address'  => 'Counter Sale / On-the-spot',
                    'city'              => config('app.store.city', 'Counter'),
                    'notes'             => $request->notes,
                ]);

                foreach ($lineItemsData as $line) {
                    OrderItem::create([
                        'order_id'      => $order->id,
                        'product_id'    => $line['product']->id,
                        'product_name'  => $line['product']->name,
                        'product_price' => $line['unit_price'],
                        'quantity'      => $line['quantity'],
                        'subtotal'      => $line['subtotal'],
                    ]);

                    // Immediate stock reduction
                    $line['product']->decrement('stock', $line['quantity']);
                }

                return $order;
            });

            $order->load(['items.product', 'cashier']);

            return response()->json([
                'success'      => true,
                'message'      => "Order #{$order->order_number} completed successfully!",
                'order'        => [
                    'id'             => $order->id,
                    'order_number'   => $order->order_number,
                    'total'          => (float) $order->total,
                    'subtotal'       => (float) $order->subtotal,
                    'discount'       => (float) $order->discount_amount,
                    'paid_amount'    => (float) $order->paid_amount,
                    'change_amount'  => (float) $order->change_amount,
                    'payment_method' => $order->payment_method,
                    'customer_name'  => $order->customer_name,
                    'date'           => $order->created_at->format('d M Y, h:i A'),
                    'items_count'    => $order->items->sum('quantity'),
                ],
                'receipt_url'  => route('admin.pos.receipt', $order),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Daily Shift Register Summary.
     */
    public function register(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $start = \Carbon\Carbon::parse($date)->startOfDay();
        $end   = \Carbon\Carbon::parse($date)->endOfDay();

        $orders = Order::where('source', 'pos')
            ->whereBetween('created_at', [$start, $end])
            ->with(['cashier:id,name', 'items'])
            ->latest()
            ->get();

        $summary = [
            'date'           => $start->format('d M Y'),
            'total_orders'   => $orders->count(),
            'total_sales'    => (float) $orders->sum('total'),
            'total_discount' => (float) $orders->sum('discount_amount'),
            'cash_sales'     => (float) $orders->where('payment_method', 'cash')->sum('total'),
            'jazzcash_sales' => (float) $orders->where('payment_method', 'jazzcash')->sum('total'),
            'easypaisa_sales'=> (float) $orders->where('payment_method', 'easypaisa')->sum('total'),
            'card_sales'     => (float) $orders->where('payment_method', 'card')->sum('total'),
            'bank_sales'     => (float) $orders->where('payment_method', 'bank_transfer')->sum('total'),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'summary' => $summary,
                'orders'  => $orders,
            ]);
        }

        return view('admin.pos.register', compact('summary', 'orders', 'date'));
    }

    /**
     * Dedicated Thermal Receipt (80mm / 58mm).
     */
    public function receipt(Order $order)
    {
        $order->load(['items.product', 'cashier']);

        return view('admin.pos.receipt', compact('order'));
    }
}
