{{--
    Intended path: resources/views/superadmin/login.blade.php
    Route: /super-admin/login (matching the guess in auth.js — confirm
    or correct this route name/path)

    Standalone page — doesn't use x-layouts.superAdmin, same reasoning
    as every other pre-auth page in this app (login/register/callback
    pages): there's no sidebar/user session to render yet.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login · MedMart</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/fill/style.css">
    <style>
        .font-manrope{font-family:'Manrope',sans-serif}
        .font-inter{font-family:'Inter',sans-serif}
        body{ font-family:'Inter',sans-serif; color:#171E26; background:#F7FAFD; }
        .field-input{
            width:100%; background:#fff; border:1px solid #DBEBFB; border-radius:0.65rem;
            padding:0.7rem 0.9rem; font-family:'Inter',sans-serif; font-size:14px; color:#171E26;
            transition:border-color .2s ease, box-shadow .2s ease;
        }
        .field-input:focus{ outline:none; border-color:#2775E4; box-shadow:0 0 0 3px rgba(39,117,228,0.15); }
        .field-label{ display:block; font-family:'Inter',sans-serif; font-size:12.5px; font-weight:600; color:#171E26; margin-bottom:0.4rem; }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#B1D0FB]/40 blur-3xl -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-[#DBEBFB]/50 blur-3xl -z-10 pointer-events-none"></div>

        <div class="w-full max-w-[400px]">

            <div class="flex flex-col items-center mb-7">
                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center mb-3">
                    <i class="ph-fill ph-shield-check text-white text-2xl"></i>
                </div>
                <p class="font-manrope font-extrabold text-[18px] text-[#171E26]">MedMart</p>
                <p class="font-inter text-[11px] font-semibold text-[#2775E4] tracking-wider uppercase">Super Admin</p>
            </div>

            <div class="bg-white border border-[#EAF1FB] rounded-2xl shadow-sm p-6 sm:p-8">
                <h1 class="font-manrope font-bold text-[19px] text-[#171E26] mb-1">Sign in</h1>
                <p class="font-inter text-[13px] text-[#171E26]/50 mb-6">Enter your admin credentials to continue.</p>

                <div id="login-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

                <form id="login-form" class="space-y-4">
                    <div>
                        <label for="email" class="field-label">Email</label>
                        <input type="email" id="email" required autocomplete="username" class="field-input" placeholder="you@medmart.com">
                    </div>
                    <div>
                        <label for="password" class="field-label">Password</label>
                        <input type="password" id="password" required autocomplete="current-password" class="field-input" placeholder="••••••••">
                    </div>
                    <button type="submit" id="login-submit-btn"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-sm shadow-[#2775E4]/20 hover:opacity-95 transition disabled:opacity-60">
                        Sign In
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/minimal/js/super-admin/api.js') }}"></script>
    <script src="{{ asset('assets/minimal/js/super-admin/auth.js') }}"></script>
    <script>
        const loginForm = document.getElementById('login-form');
        const loginError = document.getElementById('login-error');
        const loginSubmitBtn = document.getElementById('login-submit-btn');

        loginForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            loginSubmitBtn.disabled = true;
            loginSubmitBtn.textContent = 'Signing in...';
            loginError.style.display = 'none';

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            try {
                // SuperAdminAuth.login() (in auth.js) calls POST /admin/login
                // with { email, password, device_name }, stores the token +
                // user on success, and returns the response.
                await SuperAdminAuth.login(email, password, 'super-admin-web');
                window.location.href = '{{route('dashboard')}}';
            } catch (error) {
                if (error.status === 422 && error.data && error.data.errors) {
                    loginError.textContent = Object.values(error.data.errors).flat().join(', ');
                } else {
                    loginError.textContent = error.message || 'Unable to sign in.';
                }
                loginError.style.display = 'block';
            } finally {
                loginSubmitBtn.disabled = false;
                loginSubmitBtn.textContent = 'Sign In';
            }
        });
    </script>
</body>
</html>