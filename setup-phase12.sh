#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase12"

mkdir -p app/Notifications
mkdir -p database/migrations

mv "$SRC/2025_01_12_000001_create_notifications_table.php" database/migrations/2025_01_12_000001_create_notifications_table.php

mv "$SRC/OrderStatusUpdated.php" app/Notifications/OrderStatusUpdated.php
mv "$SRC/PrescriptionReviewed.php" app/Notifications/PrescriptionReviewed.php

mv "$SRC/channels.php" routes/channels.php
mv "$SRC/OrderService.php" app/Services/Orders/OrderService.php
mv "$SRC/PrescriptionService.php" app/Services/Prescriptions/PrescriptionService.php

mv "$SRC/NotificationResource.php" app/Http/Resources/Customer/NotificationResource.php
mv "$SRC/NotificationController.php" app/Http/Controllers/Api/V1/Customer/NotificationController.php

mv "$SRC/customer.php" routes/api/customer.php
mv "$SRC/notification-debug.html" public/notification-debug.html

echo "Phase 12 files placed."