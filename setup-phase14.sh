#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase14"

mkdir -p app/Services/Billing
mkdir -p app/Console/Commands
mkdir -p database/migrations

mv "$SRC/2025_01_14_000001_create_subscription_payments_table.php" database/migrations/2025_01_14_000001_create_subscription_payments_table.php
mv "$SRC/2025_01_14_000002_add_test_account_flag_to_pharmacies_table.php" database/migrations/2025_01_14_000002_add_test_account_flag_to_pharmacies_table.php
mv "$SRC/2025_01_14_000003_add_renewal_reminder_to_subscriptions_table.php" database/migrations/2025_01_14_000003_add_renewal_reminder_to_subscriptions_table.php

mv "$SRC/Pharmacy.php" app/Models/Pharmacy.php
mv "$SRC/Subscription.php" app/Models/Subscription.php
mv "$SRC/SubscriptionPayment.php" app/Models/SubscriptionPayment.php

mv "$SRC/PharmacySubscriptionService.php" app/Services/Billing/PharmacySubscriptionService.php
mv "$SRC/PaystackWebhookController.php" app/Http/Controllers/Webhooks/PaystackWebhookController.php

mv "$SRC/SubscribeRequest.php" app/Http/Requests/Staff/SubscribeRequest.php
mv "$SRC/SubscriptionPlanResource.php" app/Http/Resources/Staff/SubscriptionPlanResource.php
mv "$SRC/SubscriptionResource.php" app/Http/Resources/Staff/SubscriptionResource.php
mv "$SRC/SubscriptionController.php" app/Http/Controllers/Api/V1/Staff/SubscriptionController.php

mv "$SRC/EnsurePharmacyHasActiveSubscription.php" app/Http/Middleware/EnsurePharmacyHasActiveSubscription.php
mv "$SRC/EnsureCustomerHasActivePharmacy.php" app/Http/Middleware/EnsureCustomerHasActivePharmacy.php

mv "$SRC/SubscriptionExpiringSoon.php" app/Notifications/SubscriptionExpiringSoon.php
mv "$SRC/ManageSubscriptionExpirations.php" app/Console/Commands/ManageSubscriptionExpirations.php

mv "$SRC/staff.php" routes/api/staff.php
mv "$SRC/staff-subscription-debug.html" public/staff-subscription-debug.html

echo "Phase 14 files placed."