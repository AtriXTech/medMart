<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing - MedMart</title>
    <link rel="stylesheet" href="{{ asset('assets/minimal/css/app.css') }}">
    <style>
        .callback-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .callback-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .spinner {
            border: 4px solid var(--border);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .status-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .status-message {
            color: var(--text-muted);
            margin-bottom: 24px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="callback-container">
        <div class="callback-card">
            <div id="loading-state">
                <div class="spinner"></div>
                <h2 class="status-title">Processing Payment</h2>
                <p class="status-message">Please wait while we confirm your payment...</p>
            </div>

            <div id="success-state" style="display: none;">
                <div style="font-size: 64px; margin-bottom: 16px;">✅</div>
                <h2 class="status-title">Payment Successful!</h2>
                <p class="status-message">Your order has been paid successfully.</p>
                <a href="/customer/orders" class="btn btn-primary">View My Orders</a>
            </div>

            <div id="error-state" style="display: none;">
                <div style="font-size: 64px; margin-bottom: 16px;">❌</div>
                <h2 class="status-title">Payment Failed</h2>
                <p class="status-message" id="error-message">There was an issue processing your payment.</p>
                <a href="/customer/orders" class="btn btn-secondary">Go to Orders</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/minimal/js/customer/api.js') }}"></script>
    <script src="{{ asset('assets/minimal/js/customer/api.js') }}"></script>
    <script>
        async function verifyPayment() {
            const urlParams = new URLSearchParams(window.location.search);
            const reference = urlParams.get('reference') || urlParams.get('trxref');
            const orderId = localStorage.getItem('pending_order_id');

            if (!reference) {
                showError('No payment reference found.');
                return;
            }

            if (!orderId) {
                showError('Order information not found. Please check your orders.');
                return;
            }

            try {
                const response = await fetch('/api/v1/customer/payments/verify', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${CustomerApi.getToken()}`,
                    },
                    body: JSON.stringify({
                        reference: reference,
                        order_id: parseInt(orderId),
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Unable to verify payment.');
                }

                if (data.status === 'success') {
                    localStorage.removeItem('pending_order_id');
                    showSuccess();
                } else {
                    showError(data.message || 'Payment verification failed.');
                }
            } catch (error) {
                showError(error.message || 'Unable to verify payment.');
            }
        }

        function showSuccess() {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('success-state').style.display = 'block';
        }

        function showError(message) {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('error-state').style.display = 'block';
            document.getElementById('error-message').textContent = message;
        }

        verifyPayment();
    </script>
</body>

</html>
