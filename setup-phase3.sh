#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase3"

mkdir -p app/Services/Purchasing
mkdir -p database/migrations
mkdir -p database/factories

mv "$SRC/PurchaseOrderStatus.php" app/Enums/PurchaseOrderStatus.php

mv "$SRC/Supplier.php" app/Models/Supplier.php
mv "$SRC/PurchaseOrder.php" app/Models/PurchaseOrder.php
mv "$SRC/PurchaseOrderItem.php" app/Models/PurchaseOrderItem.php
mv "$SRC/Batch.php" app/Models/Batch.php

mv "$SRC/PurchasingService.php" app/Services/Purchasing/PurchasingService.php
mv "$SRC/StockService.php" app/Services/Inventory/StockService.php

mv "$SRC/StoreSupplierRequest.php" app/Http/Requests/Staff/StoreSupplierRequest.php
mv "$SRC/UpdateSupplierRequest.php" app/Http/Requests/Staff/UpdateSupplierRequest.php
mv "$SRC/StorePurchaseOrderRequest.php" app/Http/Requests/Staff/StorePurchaseOrderRequest.php
mv "$SRC/ReceivePurchaseOrderItemsRequest.php" app/Http/Requests/Staff/ReceivePurchaseOrderItemsRequest.php

mv "$SRC/SupplierResource.php" app/Http/Resources/Staff/SupplierResource.php
mv "$SRC/PurchaseOrderItemResource.php" app/Http/Resources/Staff/PurchaseOrderItemResource.php
mv "$SRC/PurchaseOrderResource.php" app/Http/Resources/Staff/PurchaseOrderResource.php

mv "$SRC/SupplierController.php" app/Http/Controllers/Api/V1/Staff/SupplierController.php
mv "$SRC/PurchaseOrderController.php" app/Http/Controllers/Api/V1/Staff/PurchaseOrderController.php

mv "$SRC/2025_01_03_000001_create_suppliers_table.php" database/migrations/2025_01_03_000001_create_suppliers_table.php
mv "$SRC/2025_01_03_000002_create_purchase_orders_table.php" database/migrations/2025_01_03_000002_create_purchase_orders_table.php
mv "$SRC/2025_01_03_000003_create_purchase_order_items_table.php" database/migrations/2025_01_03_000003_create_purchase_order_items_table.php
mv "$SRC/2025_01_03_000004_add_supplier_id_to_batches_table.php" database/migrations/2025_01_03_000004_add_supplier_id_to_batches_table.php

mv "$SRC/SupplierFactory.php" database/factories/SupplierFactory.php

mv "$SRC/staff.php" routes/api/staff.php
mv "$SRC/DemoPharmacySeeder.php" database/seeders/DemoPharmacySeeder.php

echo "Phase 3 files placed."