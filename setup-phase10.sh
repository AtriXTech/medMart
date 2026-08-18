#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase10"

mv "$SRC/app_Enums_FulfillmentType.php" app/Enums/FulfillmentType.php
mv "$SRC/app_Enums_DeliveryStatus.php" app/Enums/DeliveryStatus.php

mv "$SRC/database_migrations_2025_01_10_000001_add_fulfillment_to_orders_table.php" database/migrations/2025_01_10_000001_add_fulfillment_to_orders_table.php

mv "$SRC/app_Models_Order.php" app/Models/Order.php
mv "$SRC/app_Services_Orders_CheckoutService.php" app/Services/Orders/CheckoutService.php

mv "$SRC/app_Http_Requests_Customer_CheckoutRequest.php" app/Http/Requests/Customer/CheckoutRequest.php
mv "$SRC/app_Http_Requests_Staff_UpdateDeliveryStatusRequest.php" app/Http/Requests/Staff/UpdateDeliveryStatusRequest.php

mv "$SRC/app_Http_Resources_Customer_OrderResource.php" app/Http/Resources/Customer/OrderResource.php
mv "$SRC/app_Http_Resources_Staff_OrderResource.php" app/Http/Resources/Staff/OrderResource.php

mv "$SRC/app_Http_Controllers_Api_V1_Customer_CheckoutController.php" app/Http/Controllers/Api/V1/Customer/CheckoutController.php
mv "$SRC/app_Http_Controllers_Api_V1_Staff_OrderController.php" app/Http/Controllers/Api/V1/Staff/OrderController.php

mv "$SRC/routes_api_staff.php" routes/api/staff.php

mv "$SRC/public_checkout-debug.html" public/checkout-debug.html
mv "$SRC/public_staff-order-debug.html" public/staff-order-debug.html
mv "$SRC/public_staff-prescription-debug.html" public/staff-prescription-debug.html

echo "Phase 10 files placed."