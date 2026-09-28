{{--
    Intended path: resources/views/staff/payment-callback.blade.php
    (or wherever this route currently points — it's a standalone page,
    not using x-layouts.staff, same as your original)

    CHANGE SUMMARY:
    - Every ID verifyPayment()/showSuccess()/showError() bind to is
      preserved exactly: loading-state, success-state, error-state,
      success-message, error-message.
    - verifyPayment() itself: completely unchanged — same reference/trxref
      URL param reading, same POST /staff/subscription/verify-payment,
      same success/error conditions.
    - Visual only: app.css removed (standalone Tailwind page, same as
      the rest of the migration), emoji swapped for Phosphor icons,
      spinner restyled with brand colors.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing - MedMart</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/fill/style.css">
    <style>
        .font-manrope{font-family:'Manrope',sans-serif}
        .font-inter{font-family:'Inter',sans-serif}
        body{ font-family:'Inter',sans-serif; color:#171E26; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 bg-gradient-to-br from-[#E9F3FE] via-[#DBEBFB] to-[#B1D0FB]">

    <div class="bg-white rounded-2xl shadow-lg border border-[#EAF1FB] p-8 md:p-10 text-center max-w-[440px] w-full">

        <div id="loading-state">
            <div class="mx-auto mb-6 h-14 w-14 rounded-full border-4 border-[#DBEBFB] border-t-[#2775E4] animate-spin"></div>
            <h2 class="font-manrope font-extrabold text-[20px] text-[#171E26] mb-2">Processing Payment</h2>
            <p class="font-inter text-[14px] text-[#171E26]/55">Please wait while we verify your payment...</p>
        </div>

        <div id="success-state" style="display: none;">
            <div class="mx-auto mb-5 h-16 w-16 rounded-full bg-[#E9F8EF] flex items-center justify-center">
                <i class="ph-fill ph-check-circle text-[#1F7A44] text-4xl"></i>
            </div>
            <h2 class="font-manrope font-extrabold text-[20px] text-[#171E26] mb-2">Payment Successful!</h2>
            <p id="success-message" class="font-inter text-[14px] text-[#171E26]/55 mb-6">Your payment has been processed successfully.</p>
            <a href="/staff/dashboard"
               class="inline-block w-full py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-md shadow-[#2775E4]/20 hover:opacity-95 transition">
                Go to Dashboard
            </a>
        </div>

        <div id="error-state" style="display: none;">
            <div class="mx-auto mb-5 h-16 w-16 rounded-full bg-[#FDEDEC] flex items-center justify-center">
                <i class="ph-fill ph-x-circle text-[#9C3A32] text-4xl"></i>
            </div>
            <h2 class="font-manrope font-extrabold text-[20px] text-[#171E26] mb-2">Payment Failed</h2>
            <p id="error-message" class="font-inter text-[14px] text-[#171E26]/55 mb-6">There was an issue processing your payment.</p>
            <div class="flex items-center gap-3">
                <a href="/staff/subscription"
                   class="flex-1 py-3 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[14px] text-[#171E26] hover:bg-[#F7FAFD] transition">
                    Try Again
                </a>
                <a href="/staff/dashboard"
                   class="flex-1 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-md shadow-[#2775E4]/20 hover:opacity-95 transition">
                    Go to Dashboard
                </a>
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