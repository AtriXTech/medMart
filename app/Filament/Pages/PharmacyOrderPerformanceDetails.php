<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Filament\Widgets\PharmacyOrderStatusDistribution;
use App\Models\Pharmacy;
use App\Models\Order;
use Filament\Pages\Page;

class PharmacyOrderPerformanceDetails extends Page
{
    protected static ?string $slug = 'orders/pharmacy/{pharmacy}';

    protected static ?string $title = 'Pharmacy Order Performance';

    protected string $view = 'filament.pages.pharmacy-order-performance-details';

    protected static bool $shouldRegisterNavigation = false;

    public Pharmacy $pharmacy;

    public function mount(string|Pharmacy $pharmacy): void
    {
        if ($pharmacy instanceof Pharmacy) {
            $this->pharmacy = $pharmacy;

            return;
        }

        $this->pharmacy = Pharmacy::findOrFail($pharmacy);
    }

  protected function getViewData(): array
{
    $orders = $this->pharmacy->orders()
        ->withoutGlobalScopes();

    $totalOrders = (clone $orders)->count();

    $processedOrders = (clone $orders)
        ->whereIn('status', [
            OrderStatus::Processing->value,
            OrderStatus::ReadyForPickup->value,
            OrderStatus::Completed->value,
        ])
        ->count();

    $completedOrders = (clone $orders)
        ->where('status', OrderStatus::Completed->value)
        ->count();

    $cancelledOrders = (clone $orders)
        ->where('status', OrderStatus::Cancelled->value)
        ->count();

    $completionBase = $completedOrders + $cancelledOrders;

    $completionRate = $completionBase > 0
        ? round(($completedOrders / $completionBase) * 100, 1)
        : 0;

    $statusCounts = (clone $orders)
        ->selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    return [
        'totalOrders' => $totalOrders,
        'processedOrders' => $processedOrders,
        'completedOrders' => $completedOrders,
        'cancelledOrders' => $cancelledOrders,
        'completionRate' => $completionRate,
        'statusCounts' => $statusCounts,
    ];
}






}