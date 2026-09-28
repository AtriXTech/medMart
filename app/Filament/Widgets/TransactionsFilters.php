<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TransactionsFilters extends Widget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.transactions-filters';
}