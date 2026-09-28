<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\SettlementAccountsTable;
use Filament\Pages\Page;

class SettlementAccounts extends Page
{
    protected static ?string $navigationLabel = 'Settlement Accounts';

    protected static ?string $title = 'Settlement Accounts';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.settlement-accounts';

    protected function getHeaderWidgets(): array
    {
        return [
            SettlementAccountsTable::class,
        ];
    }
}