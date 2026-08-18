#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase8"

mkdir -p app/Services/Orders
mkdir -p app/Services/Payments
mkdir -p app/Http/Controllers/Webhooks
mkdir -p database/migrations

mv "$SRC/app_Enums_OrderStatus.php" app/Enums/OrderStatus.php
mv "$SRC/app_Enums_PaymentStatus.php" app/Enums/PaymentStatus.php

mv "$SRC/database_migrations_2025_01_08_000001_create_orders_table.php" database/migrations/2025_01_08_000001_create_orders_table.php
mv "$SRC/database_migrations_2025_01_08_000002_create_order_items_table.php" database/migrations/2025_01_08_000002_create_order_items_table.php
mv "$SRC/database_migrations_2025_01_08_000003_create_payments_table.php" database/migrations/2025_01_08_000003_create_payments_table.php

mv "$SRC/app_Models_Order.php" app/Models/Order.php
mv "$SRC/app_Models_OrderItem.php" app/Models/OrderItem.php
mv "$SRC/app_Models_Payment.php" app/Models/Payment.php

mv "$SRC/app_Services_Orders_CheckoutService.php" app/Services/Orders/CheckoutService.php
mv "$SRC/app_Services_Orders_OrderService.php" app/Services/Orders/OrderService.php
mv "$SRC/app_Services_Payments_PaystackService.php" app/Services/Payments/PaystackService.php
mv "$SRC/app_Services_Payments_PaymentService.php" app/Services/Payments/PaymentService.php

mv "$SRC/app_Http_Middleware_VerifyPaystackSignature.php" app/Http/Middleware/VerifyPaystackSignature.php

mv "$SRC/app_Http_Requests_Customer_CancelOrderRequest.php" app/Http/Requests/Customer/CancelOrderRequest.php
mv "$SRC/app_Http_Requests_Staff_UpdateOrderStatusRequest.php" app/Http/Requests/Staff/UpdateOrderStatusRequest.php

mv "$SRC/app_Http_Resources_Customer_OrderItemResource.php" app/Http/Resources/Customer/OrderItemResource.php
mv "$SRC/app_Http_Resources_Customer_OrderResource.php" app/Http/Resources/Customer/OrderResource.php
mv "$SRC/app_Http_Resources_Staff_OrderItemResource.php" app/Http/Resources/Staff/OrderItemResource.php
mv "$SRC/app_Http_Resources_Staff_OrderResource.php" app/Http/Resources/Staff/OrderResource.php

mv "$SRC/app_Http_Controllers_Api_V1_Customer_CheckoutController.php" app/Http/Controllers/Api/V1/Customer/CheckoutController.php
mv "$SRC/app_Http_Controllers_Api_V1_Customer_PaymentController.php" app/Http/Controllers/Api/V1/Customer/PaymentController.php
mv "$SRC/app_Http_Controllers_Api_V1_Customer_OrderController.php" app/Http/Controllers/Api/V1/Customer/OrderController.php
mv "$SRC/app_Http_Controllers_Api_V1_Staff_OrderController.php" app/Http/Controllers/Api/V1/Staff/OrderController.php
mv "$SRC/app_Http_Controllers_Webhooks_PaystackWebhookController.php" app/Http/Controllers/Webhooks/PaystackWebhookController.php

mv "$SRC/routes_api_webhooks.php" routes/api/webhooks.php
mv "$SRC/routes_api.php" routes/api.php
mv "$SRC/routes_api_customer.php" routes/api/customer.php
mv "$SRC/routes_api_staff.php" routes/api/staff.php

echo "Phase 8 files placed."