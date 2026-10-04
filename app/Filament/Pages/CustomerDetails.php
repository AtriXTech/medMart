<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Models\Customer;
use Filament\Pages\Page;

class CustomerDetails extends Page
{
    protected static ?string $slug = 'customers/{customer}';

    protected static ?string $title = 'Customer Details';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.customer-details';

    public Customer $customer;

    public function mount(string|Customer $customer): void
    {
        if ($customer instanceof Customer) {
            $this->customer = $customer;
            return;
        }

        $this->customer = Customer::findOrFail($customer);
    }

    protected function getViewData(): array
    {
        $orders = $this->customer->orders()
            ->withoutGlobalScopes();

        $totalOrders = (clone $orders)->count();

        $processedOrders = (clone $orders)
            ->whereIn('status', [
                OrderStatus::Processing->value,
                OrderStatus::ReadyForPickup->value,
                OrderStatus::Completed->value,
            ])
            ->count();
        $readyForPickup = (clone $orders)
            ->whereIn('status', [
                OrderStatus::ReadyForPickup->value,
            ])
            ->count();
        

        $totalSpent = (clone $orders)
            ->where('status', OrderStatus::Completed->value)
            ->sum('total');

        $linkedPharmacies = $this->customer
            ->pharmacies()
            ->count();

        $pharmacyUsage = $this->customer
            ->orders()
            ->withoutGlobalScopes()
            ->with('pharmacy')
            ->selectRaw('pharmacy_id, COUNT(*) as orders_count, SUM(CASE WHEN status = ? THEN total ELSE 0 END) as total_spent', [
                OrderStatus::Completed->value,
            ])
            ->groupBy('pharmacy_id')
            ->orderByDesc('orders_count')
            ->get();

        $transactions = $this->customer
            ->orders()
            ->withoutGlobalScopes()
            ->with('pharmacy')
            ->latest()
            ->get();

        return [
            'totalOrders' => $totalOrders,
            'processedOrders' => $processedOrders,
            'readyForPickup'=> $readyForPickup,
            'totalSpent' => $totalSpent,
            'linkedPharmacies' => $linkedPharmacies,
            'pharmacyUsage' => $pharmacyUsage,
            'transactions' => $transactions,
        ];
    }
}
