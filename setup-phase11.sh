#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase11"

mv "$SRC/2025_01_11_000001_add_suspension_to_customer_pharmacy_links_table.php" database/migrations/2025_01_11_000001_add_suspension_to_customer_pharmacy_links_table.php
mv "$SRC/CustomerPharmacyLink.php" app/Models/CustomerPharmacyLink.php
mv "$SRC/EnsureCustomerHasActivePharmacy.php" app/Http/Middleware/EnsureCustomerHasActivePharmacy.php
mv "$SRC/CustomerLinkResource.php" app/Http/Resources/Staff/CustomerLinkResource.php
mv "$SRC/CustomerController.php" app/Http/Controllers/Api/V1/Staff/CustomerController.php
mv "$SRC/staff.php" routes/api/staff.php
mv "$SRC/staff-customer-debug.html" public/staff-customer-debug.html

echo "Phase 11 files placed."