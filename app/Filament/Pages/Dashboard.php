<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Override;

class Dashboard extends BaseDashboard
{
    // public function getColu(): int | string | array
    #[Override]
    public function getColumns(): int|array
    {
        // return parent::getColumns();
           return [
            'default' => 1,
            'sm' => 2,
            'md' => 12,
            'lg' => 12,
            'xl' => 12,
        ];
    }
    
     
    
}