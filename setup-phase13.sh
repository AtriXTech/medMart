#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase13"

mv "$SRC/DashboardController.php" app/Http/Controllers/Api/V1/Staff/DashboardController.php
mv "$SRC/staff.php" routes/api/staff.php
mv "$SRC/staff-dashboard-debug.html" public/staff-dashboard-debug.html

echo "Phase 13 files placed."