#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase9"

mkdir -p app/Services/Prescriptions
mkdir -p database/migrations

mv "$SRC/app_Enums_PrescriptionStatus.php" app/Enums/PrescriptionStatus.php

mv "$SRC/database_migrations_2025_01_09_000001_create_prescriptions_table.php" database/migrations/2025_01_09_000001_create_prescriptions_table.php

mv "$SRC/app_Models_Prescription.php" app/Models/Prescription.php

mv "$SRC/app_Services_Prescriptions_PrescriptionService.php" app/Services/Prescriptions/PrescriptionService.php
mv "$SRC/app_Services_Orders_CheckoutService.php" app/Services/Orders/CheckoutService.php

mv "$SRC/app_Http_Requests_Customer_UploadPrescriptionRequest.php" app/Http/Requests/Customer/UploadPrescriptionRequest.php
mv "$SRC/app_Http_Requests_Staff_ReviewPrescriptionRequest.php" app/Http/Requests/Staff/ReviewPrescriptionRequest.php

mv "$SRC/app_Http_Resources_Customer_PrescriptionResource.php" app/Http/Resources/Customer/PrescriptionResource.php
mv "$SRC/app_Http_Resources_Staff_PrescriptionResource.php" app/Http/Resources/Staff/PrescriptionResource.php

mv "$SRC/app_Http_Controllers_Api_V1_Customer_PrescriptionController.php" app/Http/Controllers/Api/V1/Customer/PrescriptionController.php
mv "$SRC/app_Http_Controllers_Api_V1_Staff_PrescriptionController.php" app/Http/Controllers/Api/V1/Staff/PrescriptionController.php

mv "$SRC/routes_api_customer.php" routes/api/customer.php
mv "$SRC/routes_api_staff.php" routes/api/staff.php

mv "$SRC/public_prescription-debug.html" public/prescription-debug.html
mv "$SRC/public_staff-prescription-debug.html" public/staff-prescription-debug.html

echo "Phase 9 files placed."