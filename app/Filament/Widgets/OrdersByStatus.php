<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class OrdersByStatus extends Widget
{
    protected  string $view = 'filament.widgets.orders-by-status';
    // protected static bool $isDiscovered = false;
    protected static ?int $sort = 3;

protected int | string | array $columnSpan = [
    'md' => 6, // 6 out of 12 columns (exactly 50% width)
];
    protected function getViewData(): array
    {
        $statuses = ['completed' => ['label' => 'Completed', 'color' => '#08AEBC',], 'processing' => ['label' => 'Processing', 'color' => '#2775E4',], 'paid' => ['label' => 'Paid', 'color' => '#B1D0FB',], 'received' => ['label' => 'Received', 'color' => '#058A98',], 'pending_payment' => ['label' => 'Pending Payment', 'color' => '#FBBF24',], 'ready_for_pickup' => ['label' => 'Ready for Pickup', 'color' => '#2775E4',], 'cancelled' => ['label' => 'Cancelled', 'color' => '#F87171',],];
        $counts = Order::withoutGlobalScopes()->selectRaw('status, COUNT(*) as total')->whereIn('status', array_keys($statuses))->groupBy('status')->pluck('total', 'status');
        $maxCount = max($counts->max() ?? 1, 1);
        $orders = collect($statuses)->map(function (array $status, string $key) use ($counts, $maxCount) {
            $count = (int) ($counts[$key] ?? 0);
            return ['key' => $key, 'label' => $status['label'], 'count' => $count, 'color' => $status['color'], 'percentage' => round(($count / $maxCount) * 100),];
        })->values();
        return ['orders' => $orders,];
    }
}
