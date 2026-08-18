<?php

declare(strict_types=1);

namespace App\Enums;

enum StaffRole: string
{
    case Owner = 'owner';
    case Pharmacist = 'pharmacist';
    case InventoryManager = 'inventory_manager';
    case Cashier = 'cashier';
}
