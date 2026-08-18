#!/bin/bash
set -e

SRC="$HOME/Downloads/medmart-phase6"

mkdir -p app/Events
mkdir -p app/Services/Realtime

mv "$SRC/StockLevelChanged.php" app/Events/StockLevelChanged.php
mv "$SRC/ProductPriceChanged.php" app/Events/ProductPriceChanged.php
mv "$SRC/ProductAvailabilityChanged.php" app/Events/ProductAvailabilityChanged.php

mv "$SRC/BroadcastService.php" app/Services/Realtime/BroadcastService.php

mv "$SRC/channels.php" routes/channels.php
mv "$SRC/BroadcastServiceProvider.php" app/Providers/BroadcastServiceProvider.php

mv "$SRC/StockService.php" app/Services/Inventory/StockService.php
mv "$SRC/ProductController.php" app/Http/Controllers/Api/V1/Staff/ProductController.php

mv "$SRC/realtime-debug.html" public/realtime-debug.html

echo "Phase 6 files placed."