#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase1"

mkdir -p app/Enums
mkdir -p app/Exceptions
mkdir -p app/Http/Controllers/Api/V1/Staff
mkdir -p app/Http/Controllers/Api/V1/Customer
mkdir -p app/Http/Middleware
mkdir -p app/Http/Requests/Staff
mkdir -p app/Http/Requests/Customer
mkdir -p app/Http/Resources/Staff
mkdir -p app/Http/Resources/Customer
mkdir -p app/Models
mkdir -p app/Services/Auth
mkdir -p app/Traits
mkdir -p database/migrations
mkdir -p database/factories
mkdir -p database/seeders
mkdir -p routes/api

mv "$SRC/app_Enums_BillingInterval.php" app/Enums/BillingInterval.php
mv "$SRC/app_Enums_PharmacyStatus.php" app/Enums/PharmacyStatus.php
mv "$SRC/app_Enums_StaffRole.php" app/Enums/StaffRole.php
mv "$SRC/app_Enums_SubscriptionStatus.php" app/Enums/SubscriptionStatus.php

mv "$SRC/app_Exceptions_PharmacyAccessSuspendedException.php" app/Exceptions/PharmacyAccessSuspendedException.php

mv "$SRC/app_Http_Controllers_Api_V1_Staff_AuthController.php" app/Http/Controllers/Api/V1/Staff/AuthController.php
mv "$SRC/app_Http_Controllers_Api_V1_Customer_AuthController.php" app/Http/Controllers/Api/V1/Customer/AuthController.php

mv "$SRC/app_Http_Middleware_EnsureRequestIsFromStaff.php" app/Http/Middleware/EnsureRequestIsFromStaff.php
mv "$SRC/app_Http_Middleware_EnsureRequestIsFromCustomer.php" app/Http/Middleware/EnsureRequestIsFromCustomer.php

mv "$SRC/app_Http_Requests_Staff_LoginRequest.php" app/Http/Requests/Staff/LoginRequest.php
mv "$SRC/app_Http_Requests_Staff_ForgotPasswordRequest.php" app/Http/Requests/Staff/ForgotPasswordRequest.php
mv "$SRC/app_Http_Requests_Staff_ResetPasswordRequest.php" app/Http/Requests/Staff/ResetPasswordRequest.php

mv "$SRC/app_Http_Requests_Customer_LoginRequest.php" app/Http/Requests/Customer/LoginRequest.php
mv "$SRC/app_Http_Requests_Customer_ForgotPasswordRequest.php" app/Http/Requests/Customer/ForgotPasswordRequest.php
mv "$SRC/app_Http_Requests_Customer_ResetPasswordRequest.php" app/Http/Requests/Customer/ResetPasswordRequest.php

mv "$SRC/app_Http_Resources_Staff_AuthenticatedStaffResource.php" app/Http/Resources/Staff/AuthenticatedStaffResource.php
mv "$SRC/app_Http_Resources_Customer_AuthenticatedCustomerResource.php" app/Http/Resources/Customer/AuthenticatedCustomerResource.php

mv "$SRC/app_Models_Pharmacy.php" app/Models/Pharmacy.php
mv "$SRC/app_Models_SubscriptionPlan.php" app/Models/SubscriptionPlan.php
mv "$SRC/app_Models_Subscription.php" app/Models/Subscription.php
mv "$SRC/app_Models_User.php" app/Models/User.php
mv "$SRC/app_Models_Customer.php" app/Models/Customer.php

mv "$SRC/app_Services_Auth_StaffAuthService.php" app/Services/Auth/StaffAuthService.php
mv "$SRC/app_Services_Auth_CustomerAuthService.php" app/Services/Auth/CustomerAuthService.php

mv "$SRC/app_Traits_BelongsToPharmacy.php" app/Traits/BelongsToPharmacy.php

rm -f database/migrations/0001_01_01_000000_create_users_table.php
mv "$SRC/database_migrations_2025_01_01_000001_create_pharmacies_table.php" database/migrations/2025_01_01_000001_create_pharmacies_table.php
mv "$SRC/database_migrations_2025_01_01_000002_create_subscription_plans_table.php" database/migrations/2025_01_01_000002_create_subscription_plans_table.php
mv "$SRC/database_migrations_2025_01_01_000003_create_subscriptions_table.php" database/migrations/2025_01_01_000003_create_subscriptions_table.php
mv "$SRC/database_migrations_2025_01_01_000004_create_customers_table.php" database/migrations/2025_01_01_000004_create_customers_table.php
mv "$SRC/database_migrations_2025_01_01_000005_create_users_table.php" database/migrations/2025_01_01_000005_create_users_table.php

mv "$SRC/database_factories_PharmacyFactory.php" database/factories/PharmacyFactory.php
mv "$SRC/database_factories_UserFactory.php" database/factories/UserFactory.php
mv "$SRC/database_factories_CustomerFactory.php" database/factories/CustomerFactory.php
mv "$SRC/database_factories_SubscriptionPlanFactory.php" database/factories/SubscriptionPlanFactory.php

mv "$SRC/database_seeders_DatabaseSeeder.php" database/seeders/DatabaseSeeder.php
mv "$SRC/database_seeders_DemoPharmacySeeder.php" database/seeders/DemoPharmacySeeder.php

mv "$SRC/routes_api.php" routes/api.php
mv "$SRC/routes_api_customer.php" routes/api/customer.php
mv "$SRC/routes_api_staff.php" routes/api/staff.php

echo "Phase 1 files placed."