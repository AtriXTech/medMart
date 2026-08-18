#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase4"

mkdir -p app/Services/Sales
mkdir -p database/migrations

mv "$SRC/PaymentMethod.php" app/Enums/PaymentMethod.php
mv "$SRC/InsufficientStockException.php" app/Exceptions/InsufficientStockException.php

mv "$SRC/Sale.php" app/Models/Sale.php
mv "$SRC/SaleItem.php" app/Models/SaleItem.php
mv "$SRC/StockMovement.php" app/Models/StockMovement.php

mv "$SRC/StockService.php" app/Services/Inventory/StockService.php
mv "$SRC/PosSaleService.php" app/Services/Sales/PosSaleService.php

mv "$SRC/CreateSaleRequest.php" app/Http/Requests/Staff/CreateSaleRequest.php

mv "$SRC/SaleItemResource.php" app/Http/Resources/Staff/SaleItemResource.php
mv "$SRC/SaleResource.php" app/Http/Resources/Staff/SaleResource.php

mv "$SRC/SaleController.php" app/Http/Controllers/Api/V1/Staff/SaleController.php

mv "$SRC/2025_01_04_000001_add_reference_to_stock_movements_table.php" database/migrations/2025_01_04_000001_add_reference_to_stock_movements_table.php
mv "$SRC/2025_01_04_000002_create_sales_table.php" database/migrations/2025_01_04_000002_create_sales_table.php
mv "$SRC/2025_01_04_000003_create_sale_items_table.php" database/migrations/2025_01_04_000003_create_sale_items_table.php

mv "$SRC/staff.php" routes/api/staff.php

echo "Phase 4 files placed."