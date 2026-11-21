<div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-900/60 shadow-sm">
    <table class="min-w-full divide-y divide-slate-800 text-left text-sm">
        @if(isset($headers))
            <thead class="bg-slate-950/60 text-xs uppercase font-semibold text-slate-400">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-5 py-3.5">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-slate-800/60 text-slate-300">
            {{ $slot }}
        </tbody>
    </table>
</div>
