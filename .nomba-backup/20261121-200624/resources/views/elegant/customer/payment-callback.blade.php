{{--
    Intended path: resources/views/customer/payment-callback.blade.php
    (standalone page — does not use x-layouts.customer, same as the
    original, since it's a full-screen redirect target)

    CHANGE SUMMARY (vs. previous version):
    - UNCHANGED: every ID (loading-state, success-state, error-state,
      error-message), verifyPayment() logic in full (reference/trxref
      extraction, pending_order_id read, the fetch() POST to
      /api/v1/customer/payments/verify with Bearer token, the
      localStorage.removeItem on success), the single api.js script tag,
      the Tailwind/fonts/Phosphor CDN setup.
    - FIXED (the only change): the two decorative blur circles were
      `position: absolute` with no z-index, while the white card itself
      is `position: static`. Per CSS stacking rules, ANY positioned
      element paints above static ones by default, regardless of DOM
      order — so both blobs were rendering on top of the card, not
      behind it. Invisible on wide screens because the corner-anchored
      384px/288px blobs (positioned relative to the viewport, since no
      ancestor has `relative`) don't reach the centered card. On a
      narrow mobile viewport the same fixed-pixel blob covers proportionally
      much more of the screen and lands over the bottom of the card —
      right where "View My Orders" sits — dulling its color and, since
      the div still has a real clickable box even though blurred,
      intercepting taps. Added `-z-10` (same technique used on the error
      pages) and `pointer-events-none` to both circles so they always
      render behind the card and never capture clicks, on any screen size.
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
        body{ font-family:'Inter',sans-serif; color:#171E26; background:#F7FAFD; }
        .spinner-ring{
            border: 4px solid #EAF1FB;
            border-top: 4px solid #2775E4;
            border-radius: 50%;
            width: 52px;
            height: 52px;
            animation: spin 0.9s linear infinite;
            margin: 0 auto 24px;
        }
        @keyframes spin { 0%{ transform: rotate(0deg); } 100%{ transform: rotate(360deg); } }
        @media (prefers-reduced-motion: reduce){ .spinner-ring{ animation: none; } }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center p-6">
         <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/40 blur-3xl -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/50 blur-3xl -z-10 pointer-events-none"></div>
        <div class="bg-white border border-[#EAF1FB] rounded-2xl shadow-sm p-8 sm:p-10 text-center max-w-[400px] w-full">

            <div id="loading-state">
                <div class="spinner-ring"></div>
                <h2 class="font-manrope font-extrabold text-[19px] text-[#171E26] mb-2">Processing Payment</h2>
                <p class="font-inter text-[14px] text-[#171E26]/55">Please wait while we confirm your payment...</p>
            </div>

            <div id="success-state" style="display: none;">
                <div class="mx-auto mb-5 h-16 w-16 rounded-full flex items-center justify-center" style="background:#E9F8EF">
                    <i class="ph-fill ph-check-circle text-4xl" style="color:#1F7A44"></i>
                </div>
                <h2 class="font-manrope font-extrabold text-[19px] text-[#171E26] mb-2">Payment Successful!</h2>
                <p class="font-inter text-[14px] text-[#171E26]/55 mb-7">Your order has been paid successfully.</p>
                <a href="/customer/orders"
                   class="inline-block w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-md shadow-[#2775E4]/20 hover:opacity-95 transition">
                    View My Orders
                </a>
            </div>

            <div id="error-state" style="display: none;">
                <div class="mx-auto mb-5 h-16 w-16 rounded-full flex items-center justify-center" style="background:#FDEDEC">
                    <i class="ph-fill ph-x-circle text-4xl" style="color:#9C3A32"></i>
                </div>
                <h2 class="font-manrope font-extrabold text-[19px] text-[#171E26] mb-2">Payment Failed</h2>
                <p id="error-message" class="font-inter text-[14px] text-[#171E26]/55 mb-7">There was an issue processing your payment.</p>
                <a href="/customer/orders"
                   class="inline-block w-full sm:w-auto px-6 py-3 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[14px] text-[#171E26] hover:bg-[#F7FAFD] transition">
                    Go to Orders
                </a>
            </div>
        </div>
    </div>

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