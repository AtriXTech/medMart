#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase7"

mkdir -p app/Services/Cart
mkdir -p database/migrations

mv "$SRC/2025_01_07_000001_create_carts_table.php" database/migrations/2025_01_07_000001_create_carts_table.php
mv "$SRC/2025_01_07_000002_create_cart_items_table.php" database/migrations/2025_01_07_000002_create_cart_items_table.php

mv "$SRC/Cart.php" app/Models/Cart.php
mv "$SRC/CartItem.php" app/Models/CartItem.php
mv "$SRC/Customer.php" app/Models/Customer.php

mv "$SRC/CartService.php" app/Services/Cart/CartService.php

mv "$SRC/EnsureCustomerHasActivePharmacy.php" app/Http/Middleware/EnsureCustomerHasActivePharmacy.php

mv "$SRC/AddToCartRequest.php" app/Http/Requests/Customer/AddToCartRequest.php
mv "$SRC/UpdateCartItemRequest.php" app/Http/Requests/Customer/UpdateCartItemRequest.php

mv "$SRC/ProductResource.php" app/Http/Resources/Customer/ProductResource.php
mv "$SRC/CartItemResource.php" app/Http/Resources/Customer/CartItemResource.php
mv "$SRC/CartResource.php" app/Http/Resources/Customer/CartResource.php

mv "$SRC/ProductController.php" app/Http/Controllers/Api/V1/Customer/ProductController.php
mv "$SRC/CartController.php" app/Http/Controllers/Api/V1/Customer/CartController.php

mv "$SRC/customerAg.php" routes/api/customer.php
mv "$SRC/DemoPharmacySeeder.php" database/seeders/DemoPharmacySeeder.php

echo "Phase 7 files placed."