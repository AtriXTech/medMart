{{--
    Intended path: resources/views/customer/product-detail.blade.php

    CHANGE SUMMARY:
    - Every ID product-detail.js binds to is preserved exactly: product-error,
      product-loading, product-content.
    - #product-error / #product-loading / #product-content use plain
      inline style="display:none" matching what the JS toggles.
    - ADDED: #image-modal — static markup for the click-to-zoom product image
      lightbox. Critical overlay positioning set via inline style, not
      Tailwind classes, same defensive pattern used on every other modal in
      this project. Closable via the X button or clicking the backdrop.
--}}
<x-layouts.customer title="Product Details" active="products">

    <div id="product-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div id="product-loading" class="rounded-2xl bg-white border border-[#EAF1FB] p-4 md:p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="skel h-[220px] w-full md:w-[280px] flex-shrink-0"></div>
            <div class="flex-1 space-y-3">
                <div class="skel h-7 w-2/3"></div>
                <div class="skel h-5 w-1/3"></div>
                <div class="skel h-16 w-full"></div>
            </div>
        </div>
    </div>

    <div id="product-content" style="display: none;"></div>

    {{-- ================= IMAGE ZOOM MODAL ================= --}}
    <div id="image-modal"
         style="display:none; position:fixed; inset:0; z-index:100; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.75); padding:24px 16px;">
        <div class="relative max-w-2xl w-full">
            <button type="button" id="close-image-modal-btn" aria-label="Close"
                    class="absolute -top-12 right-0 md:-top-4 md:-right-4 h-9 w-9 flex items-center justify-center rounded-full bg-white text-[#171E26] shadow-lg hover:bg-[#F7FAFD] transition">
                <i class="ph ph-x text-xl"></i>
            </button>
            <img id="image-modal-img" src="" alt="" class="w-full max-h-[80vh] object-contain rounded-2xl bg-white">
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/product-detail.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>