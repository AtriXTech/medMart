<x-filament-panels::page>
    <div class="space-y-6 font-[Inter,sans-serif]">
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($roles as $role)
                <div class="rounded-2xl border border-[#171E26]/10 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">{{ $role['label'] }}</p>
                    <p class="mt-2 text-xl font-bold text-[#171E26]">{{ \App\Payments\Enums\Gateway::from($role['active'])->label() }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($cards as $card)
                            @if ($card['key'] !== $role['active'])
                                <button
                                    type="button"
                                    wire:click="mountAction('switchGateway', { role: '{{ $role['key'] }}', gateway: '{{ $card['key'] }}' })"
                                    @disabled(! $card['enabled'] || ! $card['configured'])
                                    class="rounded-lg border border-[#2775E4] px-3 py-1.5 text-[13px] font-semibold text-[#2775E4] transition hover:bg-[#2775E4] hover:text-white disabled:cursor-not-allowed disabled:border-[#171E26]/15 disabled:text-[#171E26]/30 disabled:hover:bg-transparent disabled:hover:text-[#171E26]/30"
                                >
                                    Switch to {{ $card['label'] }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($cards as $card)
                <div class="rounded-2xl border border-[#171E26]/10 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h3 class="text-lg font-bold text-[#171E26]">{{ $card['label'] }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($card['roles'] as $roleLabel)
                                <span class="rounded-full bg-[#E8F1FD] px-2.5 py-1 text-[11px] font-semibold text-[#2775E4]">Active: {{ $roleLabel }}</span>
                            @endforeach
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $card['configured'] ? 'bg-[#E9F8EF] text-[#1F7A44]' : 'bg-[#FDEDEC] text-[#9C3A32]' }}">
                                {{ $card['configured'] ? 'Configured' : 'Not configured' }}
                            </span>
                            @if ($card['configured'])
                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $card['test_mode'] ? 'bg-[#FFF4E0] text-[#9A6200]' : 'bg-[#171E26] text-white' }}">
                                    {{ $card['test_mode'] ? 'Test mode' : 'Live mode' }}
                                </span>
                            @endif
                            @if (! $card['enabled'])
                                <span class="rounded-full bg-[#171E26]/10 px-2.5 py-1 text-[11px] font-semibold text-[#171E26]/60">Disabled</span>
                            @endif
                        </div>
                    </div>

                    @if (! $card['configured'])
                        <div class="mt-4 rounded-lg bg-[#FDEDEC] p-3 text-[13px] text-[#9C3A32]">
                            Add these to your environment file, then clear the config cache:
                            <span class="mt-1 block font-mono text-[12px]">{{ implode(', ', $card['missing']) }}</span>
                        </div>
                    @endif

                    <dl class="mt-4 grid grid-cols-4 gap-3 text-center">
                        <div class="rounded-lg bg-[#F6F8FA] p-3">
                            <dt class="text-[11px] text-[#171E26]/50">Paid (30d)</dt>
                            <dd class="mt-1 text-lg font-bold text-[#171E26]">{{ $card['usage']['paid'] ?? 0 }}</dd>
                        </div>
                        <div class="rounded-lg bg-[#F6F8FA] p-3">
                            <dt class="text-[11px] text-[#171E26]/50">Failed (30d)</dt>
                            <dd class="mt-1 text-lg font-bold text-[#171E26]">{{ $card['usage']['failed'] ?? 0 }}</dd>
                        </div>
                        <div class="rounded-lg bg-[#F6F8FA] p-3">
                            <dt class="text-[11px] text-[#171E26]/50">Unpaid (30d)</dt>
                            <dd class="mt-1 text-lg font-bold text-[#171E26]">{{ $card['usage']['unpaid'] ?? 0 }}</dd>
                        </div>
                        <div class="rounded-lg bg-[#F6F8FA] p-3">
                            <dt class="text-[11px] text-[#171E26]/50">Transfers in flight</dt>
                            <dd class="mt-1 text-lg font-bold text-[#171E26]">{{ $card['in_flight'] }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4 space-y-2">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Webhook URLs</p>
                        @foreach ($card['webhooks'] as $name => $url)
                            <div class="rounded-lg bg-[#F6F8FA] px-3 py-2">
                                <p class="text-[11px] text-[#171E26]/50">{{ $name }}</p>
                                <p class="break-all font-mono text-[12px] text-[#171E26]">{{ $url }}</p>
                            </div>
                        @endforeach
                    </div>

                    @if (isset($connection[$card['key']]))
                        <div class="mt-4 rounded-lg p-3 text-[13px] {{ $connection[$card['key']]['ok'] ? 'bg-[#E9F8EF] text-[#1F7A44]' : 'bg-[#FDEDEC] text-[#9C3A32]' }}">
                            {{ $connection[$card['key']]['message'] }}
                            @if ($connection[$card['key']]['ok'])
                                <span class="block font-semibold">Balance: ₦{{ $connection[$card['key']]['balance'] }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" wire:click="testConnection('{{ $card['key'] }}')" wire:loading.attr="disabled" class="rounded-lg bg-[#2775E4] px-3 py-1.5 text-[13px] font-semibold text-white transition hover:opacity-90 disabled:opacity-50">
                            <span wire:loading.remove wire:target="testConnection('{{ $card['key'] }}')">Test connection</span>
                            <span wire:loading wire:target="testConnection('{{ $card['key'] }}')">Testing…</span>
                        </button>
                        <button type="button" wire:click="mountAction('syncBankCodes', { gateway: '{{ $card['key'] }}' })" @disabled(! $card['configured']) class="rounded-lg border border-[#171E26]/15 px-3 py-1.5 text-[13px] font-semibold text-[#171E26] transition hover:bg-[#F6F8FA] disabled:cursor-not-allowed disabled:opacity-40">
                            Sync bank codes
                        </button>
                        <button type="button" wire:click="mountAction('toggleGateway', { gateway: '{{ $card['key'] }}', enable: {{ $card['enabled'] ? 'false' : 'true' }} })" class="rounded-lg border border-[#171E26]/15 px-3 py-1.5 text-[13px] font-semibold text-[#171E26] transition hover:bg-[#F6F8FA]">
                            {{ $card['enabled'] ? 'Disable' : 'Enable' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-[#171E26]/10 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-bold text-[#171E26]">Recent changes</h3>
            @forelse ($history as $entry)
                <div class="mt-3 flex flex-wrap items-start justify-between gap-2 border-t border-[#171E26]/5 pt-3 first:border-0 first:pt-0">
                    <div>
                        <p class="text-[14px] font-semibold text-[#171E26]">{{ $entry['summary'] }}</p>
                        @if ($entry['reason'])
                            <p class="text-[13px] text-[#171E26]/60">{{ $entry['reason'] }}</p>
                        @endif
                    </div>
                    <p class="text-[12px] text-[#171E26]/50">{{ $entry['actor'] }} · {{ $entry['at'] }}</p>
                </div>
            @empty
                <p class="mt-2 text-[13px] text-[#171E26]/50">No gateway changes yet.</p>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
