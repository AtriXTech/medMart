<div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

        <div class="grid flex-1 grid-cols-1 gap-4 md:grid-cols-2">

            {{-- From --}}
            <div>
                <label class="text-sm font-medium text-gray-700">
                    From
                </label>

                <input type="date" wire:model.live="$parent.dateFrom"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2775E4] focus:ring-[#2775E4]">
            </div>

            {{-- Until --}}
            <div>
                <label class="text-sm font-medium text-gray-700">
                    Until
                </label>

                <input type="date" wire:model.live="$parent.dateUntil"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2775E4] focus:ring-[#2775E4]">
            </div>

        </div>

        {{-- Refresh --}}
        <div>
            <button type="button" wire:click="$dispatch('refresh-settlements')"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#2775E4] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#1f63c2] md:w-auto">
                <x-heroicon-m-arrow-path class="h-4 w-4" />
                Refresh
            </button>
        </div>

    </div>
</div>
