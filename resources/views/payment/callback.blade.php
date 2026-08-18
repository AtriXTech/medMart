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
            max-width: 500px;
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .success-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .error-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .status-title {
            font-size: 24px;
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
                <p class="status-message">Please wait while we verify your payment...</p>
            </div>
            
            <div id="success-state" style="display: none;">
                <div class="success-icon">✅</div>
                <h2 class="status-title">Payment Successful!</h2>
                <p class="status-message" id="success-message">Your payment has been processed successfully.</p>
                <a href="/staff/dashboard" class="btn btn-primary">Go to Dashboard</a>
            </div>
            
            <div id="error-state" style="display: none;">
                <div class="error-icon">❌</div>
                <h2 class="status-title">Payment Failed</h2>
                <p class="status-message" id="error-message">There was an issue processing your payment.</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <a href="/staff/subscription" class="btn btn-secondary">Try Again</a>
                    <a href="/staff/dashboard" class="btn btn-primary">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/minimal/js/api.js') }}"></script>
    <script>
        async function verifyPayment() {
            const urlParams = new URLSearchParams(window.location.search);
            const reference = urlParams.get('reference') || urlParams.get('trxref');
            
            if (!reference) {
                showError('No payment reference found.');
                return;
            }
            
            try {
                const result = await Api.post('/staff/subscription/verify-payment', {
                    reference: reference
                });
                
                if (result.status === 'success' || result.message === 'Payment verified') {
                    showSuccess('Your subscription has been activated successfully!');
                } else {
                    showError('Payment could not be verified. Please contact support.');
                }
            } catch (error) {
                showError(error.message || 'Unable to verify payment.');
            }
        }

        function showSuccess(message) {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('success-state').style.display = 'block';
            document.getElementById('error-state').style.display = 'none';
            document.getElementById('success-message').textContent = message;
        }

        function showError(message) {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('success-state').style.display = 'none';
            document.getElementById('error-state').style.display = 'block';
            document.getElementById('error-message').textContent = message;
        }

        verifyPayment();
    </script>
</body>
</html>