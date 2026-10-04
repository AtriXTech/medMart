<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SettlementsFilters extends Widget
{
    protected static bool $isDiscovered = false;

     protected static ?int $sort = 1;
protected int|string|array $columnSpan = 'full';


    protected string $view = 'filament.widgets.settlements-filters';
}