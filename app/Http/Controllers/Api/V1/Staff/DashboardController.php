<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Enums\OrderStatus;
use App\Enums\PrescriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $ordersByStatus = Order::query()
            ->select('status', DB::raw('count(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $statusCounts = [];

        foreach (OrderStatus::cases() as $status) {
            $statusCounts[$status->value] = (int) ($ordersByStatus[$status->value] ?? 0);
        }

        return response()->json([
            'orders' => [
                'total' => array_sum($statusCounts),
                'by_status' => $statusCounts,
            ],
            'low_stock_products_count' => Product::whereColumn('stock_quantity', '<=', 'reorder_level')->count(),
            'pending_prescriptions_count' => Prescription::where('status', PrescriptionStatus::Pending)->count(),
            'pos_sales_today' => [
                'count' => Sale::whereDate('created_at', today())->count(),
                'total' => (float) Sale::whereDate('created_at', today())->sum('total'),
            ],
            'customer_orders_today' => [
                'count' => Order::whereDate('created_at', today())->count(),
                'total' => (float) Order::whereDate('created_at', today())->sum('total'),
            ],
        ]);
    }
}
