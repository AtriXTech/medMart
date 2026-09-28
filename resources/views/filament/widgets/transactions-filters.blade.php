<div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

        <div>
            <label class="text-sm font-medium text-gray-700">
                From
            </label>

            <input
                type="date"
                wire:model.live="$parent.dateFrom"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2775E4] focus:ring-[#2775E4]"
            >
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">
                Until
            </label>

            <input
                type="date"
                wire:model.live="$parent.dateUntil"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2775E4] focus:ring-[#2775E4]"
            >
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">
                Transaction Type
            </label>

            <select
                wire:model.live="$parent.transactionType"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2775E4] focus:ring-[#2775E4]"
            >
                <option value="">All Types</option>
                <option value="Customer Order Payment">
                    Customer Order Payment
                </option>
                <option value="Subscription Payment">
                    Subscription Payment
                </option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">
                Status
            </label>

            <select
                wire:model.live="$parent.transactionStatus"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2775E4] focus:ring-[#2775E4]"
            >
                <option value="">All Statuses</option>
                <option value="paid">Successful</option>
                <option value="unpaid">Pending</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>

    </div>
</div>