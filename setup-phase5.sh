#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase5"

mkdir -p app/Services/Pharmacy
mkdir -p app/Mail
mkdir -p resources/views/emails
mkdir -p app/Http/Controllers/Api/V1/Customer
mkdir -p database/migrations

mv "$SRC/2025_01_05_000001_add_verification_token_to_customers_table.php" database/migrations/2025_01_05_000001_add_verification_token_to_customers_table.php
mv "$SRC/2025_01_05_000002_create_pharmacy_codes_table.php" database/migrations/2025_01_05_000002_create_pharmacy_codes_table.php
mv "$SRC/2025_01_05_000003_create_customer_pharmacy_links_table.php" database/migrations/2025_01_05_000003_create_customer_pharmacy_links_table.php

mv "$SRC/PharmacyCode.php" app/Models/PharmacyCode.php
mv "$SRC/CustomerPharmacyLink.php" app/Models/CustomerPharmacyLink.php
mv "$SRC/Customer.php" app/Models/Customer.php
mv "$SRC/Pharmacy.php" app/Models/Pharmacy.php

mv "$SRC/VerifyCustomerEmailMail.php" app/Mail/VerifyCustomerEmailMail.php
mv "$SRC/verify-customer-email.blade.php" resources/views/emails/verify-customer-email.blade.php

mv "$SRC/PharmacyCodeService.php" app/Services/Pharmacy/PharmacyCodeService.php
mv "$SRC/PharmacyLinkService.php" app/Services/Pharmacy/PharmacyLinkService.php
mv "$SRC/CustomerRegistrationService.php" app/Services/Auth/CustomerRegistrationService.php
mv "$SRC/CustomerAuthService.php" app/Services/Auth/CustomerAuthService.php

mv "$SRC/GeneratePharmacyCodeRequest.php" app/Http/Requests/Staff/GeneratePharmacyCodeRequest.php
mv "$SRC/RegisterRequest.php" app/Http/Requests/Customer/RegisterRequest.php
mv "$SRC/VerifyEmailRequest.php" app/Http/Requests/Customer/VerifyEmailRequest.php
mv "$SRC/ResendVerificationRequest.php" app/Http/Requests/Customer/ResendVerificationRequest.php
mv "$SRC/JoinPharmacyRequest.php" app/Http/Requests/Customer/JoinPharmacyRequest.php
mv "$SRC/SwitchPharmacyRequest.php" app/Http/Requests/Customer/SwitchPharmacyRequest.php

mv "$SRC/PharmacyCodeResource.php" app/Http/Resources/Staff/PharmacyCodeResource.php
mv "$SRC/PharmacyResource.php" app/Http/Resources/Customer/PharmacyResource.php

mv "$SRC/PharmacyCodeController.php" app/Http/Controllers/Api/V1/Staff/PharmacyCodeController.php
mv "$SRC/EmailVerificationController.php" app/Http/Controllers/Api/V1/Customer/EmailVerificationController.php
mv "$SRC/PharmacyLinkController.php" app/Http/Controllers/Api/V1/Customer/PharmacyLinkController.php
mv "$SRC/AuthController.php" app/Http/Controllers/Api/V1/Customer/AuthController.php

mv "$SRC/staff.php" routes/api/staff.php
mv "$SRC/customerRoute.php" routes/api/customer.php
mv "$SRC/DemoPharmacySeeder.php" database/seeders/DemoPharmacySeeder.php

echo "Phase 5 files placed."