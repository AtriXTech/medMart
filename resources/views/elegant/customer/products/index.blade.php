{{--
    Intended path: resources/views/customer/products.blade.php

    CHANGE SUMMARY:
    - Added #toast-container for visual feedback on add-to-cart actions.
    - Added CSS transition classes to #products-grid for smooth view switching.
    - All existing IDs preserved.
--}}
<x-layouts.customer title="Products" active="products">

    <div class="mb-4">
        <div class="relative">
            <i class="ph-light ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35 text-[16px]"></i>
            <input type="text" id="product-search" placeholder="Search products..." class="field-input pl-10">
        </div>
    </div>

    <!-- Category Header / Back Button Bar -->
    <div id="category-header" style="display: none;" class="mb-4 flex items-center justify-between">
        <button id="back-to-categories" class="flex items-center gap-2 font-inter text-[13px] font-semibold text-[#2775E4] hover:text-[#1d5cb8] transition-colors cursor-pointer bg-transparent border-0 p-0">
            <i class="ph-bold ph-arrow-left text-[14px]"></i>
            <span id="active-category-title">Back to Categories</span>
        </button>
    </div>

    <div id="products-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div id="products-loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        <div class="skel h-[190px] w-full"></div>
        <div class="skel h-[190px] w-full"></div>
        <div class="skel h-[190px] w-full"></div>
        <div class="skel h-[190px] w-full"></div>
        <div class="skel h-[190px] w-full"></div>
    </div>

    <!-- Added transition classes for smooth opacity/slide effects -->
    <div id="products-grid" style="display: none;" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 transition-all duration-300 ease-in-out opacity-100 transform translate-y-0"></div>

    <!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-2.5 pointer-events-none">
</div>
    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/products.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>