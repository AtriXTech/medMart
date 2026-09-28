<?php

namespace App\Livewire;

use Filament\Widgets\Widget;

class OrdersByStatus extends Widget
{
    protected static bool $isDiscovered = false;
    protected string $view = 'livewire.orders-by-status';
}
