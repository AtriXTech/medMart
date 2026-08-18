#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase2"

mkdir -p app/Services/Inventory
mkdir -p database/migrations
mkdir -p database/factories

mv "$SRC/StockMovementType.php" app/Enums/StockMovementType.php

mv "$SRC/ProductCategory.php" app/Models/ProductCategory.php
mv "$SRC/Product.php" app/Models/Product.php
mv "$SRC/Batch.php" app/Models/Batch.php
mv "$SRC/StockMovement.php" app/Models/StockMovement.php

mv "$SRC/StockService.php" app/Services/Inventory/StockService.php

mv "$SRC/StoreProductCategoryRequest.php" app/Http/Requests/Staff/StoreProductCategoryRequest.php
mv "$SRC/UpdateProductCategoryRequest.php" app/Http/Requests/Staff/UpdateProductCategoryRequest.php
mv "$SRC/StoreProductRequest.php" app/Http/Requests/Staff/StoreProductRequest.php
mv "$SRC/UpdateProductRequest.php" app/Http/Requests/Staff/UpdateProductRequest.php
mv "$SRC/UpdateProductAvailabilityRequest.php" app/Http/Requests/Staff/UpdateProductAvailabilityRequest.php
mv "$SRC/StoreBatchRequest.php" app/Http/Requests/Staff/StoreBatchRequest.php
mv "$SRC/AdjustBatchQuantityRequest.php" app/Http/Requests/Staff/AdjustBatchQuantityRequest.php

mv "$SRC/ProductCategoryResource.php" app/Http/Resources/Staff/ProductCategoryResource.php
mv "$SRC/ProductResource.php" app/Http/Resources/Staff/ProductResource.php
mv "$SRC/BatchResource.php" app/Http/Resources/Staff/BatchResource.php
mv "$SRC/StockMovementResource.php" app/Http/Resources/Staff/StockMovementResource.php

mv "$SRC/ProductCategoryController.php" app/Http/Controllers/Api/V1/Staff/ProductCategoryController.php
mv "$SRC/ProductController.php" app/Http/Controllers/Api/V1/Staff/ProductController.php
mv "$SRC/BatchController.php" app/Http/Controllers/Api/V1/Staff/BatchController.php
mv "$SRC/StockMovementController.php" app/Http/Controllers/Api/V1/Staff/StockMovementController.php

mv "$SRC/2025_01_02_000001_create_product_categories_table.php" database/migrations/2025_01_02_000001_create_product_categories_table.php
mv "$SRC/2025_01_02_000002_create_products_table.php" database/migrations/2025_01_02_000002_create_products_table.php
mv "$SRC/2025_01_02_000003_create_batches_table.php" database/migrations/2025_01_02_000003_create_batches_table.php
mv "$SRC/2025_01_02_000004_create_stock_movements_table.php" database/migrations/2025_01_02_000004_create_stock_movements_table.php

mv "$SRC/ProductCategoryFactory.php" database/factories/ProductCategoryFactory.php
mv "$SRC/ProductFactory.php" database/factories/ProductFactory.php
mv "$SRC/BatchFactory.php" database/factories/BatchFactory.php

mv "$SRC/staff.php" routes/api/staff.php
mv "$SRC/DemoPharmacySeeder.php" database/seeders/DemoPharmacySeeder.php

echo "Phase 2 files placed."