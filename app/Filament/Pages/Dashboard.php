<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Override;

class Dashboard extends BaseDashboard
{
    
  protected int|array $headerWidgetsColumns = [
    'sm' => 1,
    'md' => 2,
    'lg' => 3,
];
    
     
    
}