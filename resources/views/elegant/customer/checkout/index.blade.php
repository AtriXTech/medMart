{{--
    Intended path: resources/views/customer/checkout.blade.php

    CHANGE SUMMARY:
    - Every ID checkout.js binds to is preserved exactly: checkout-error,
      checkout-loading, checkout-content. Everything else on this page is
      built dynamically by checkout.js, so nearly all the redesign work
      happens there — see the change summary in checkout.js.
    - #checkout-error / #checkout-loading / #checkout-content use plain
      inline style="display:none" matching what the JS toggles.
--}}
<x-layouts.customer title="Checkout" active="cart">

    <div id="checkout-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div id="checkout-loading" class="w-full space-y-4">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] p-4 space-y-3">
            <div class="skel h-5 w-1/3"></div>
            <div class="skel h-14 w-full"></div>
            <div class="skel h-14 w-full"></div>
        </div>
        <div class="rounded-2xl bg-white border border-[#EAF1FB] p-4 space-y-3">
            <div class="skel h-5 w-1/3"></div>
            <div class="skel h-10 w-full"></div>
        </div>
    </div>

    <div id="checkout-content" style="display: none;" class="w-full"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/checkout.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>