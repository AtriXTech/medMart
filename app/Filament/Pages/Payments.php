<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\PaymentsTable;
use Filament\Pages\Page;

class Payments extends Page
{
    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $title = 'Payments';

    protected static string | \UnitEnum | null $navigationGroup = 'Settlement Console';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.payments';

    protected function getHeaderWidgets(): array
    {
        return [
            PaymentsTable::class,
        ];
    }
}