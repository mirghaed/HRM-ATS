@php $connector = $connector ?? null; @endphp

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-semibold">منبع رزومه</label>
        <select class="w-full rounded-xl border-slate-300" name="source_id" required>
            <option value="">انتخاب کنید</option>
            @foreach($sources as $source)
                <option value="{{ $source->id }}" @selected(old('source_id', $connector?->source_id) == $source->id)>{{ $source->name }} ({{ $source->key }})</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">Driver</label>
        <input class="w-full rounded-xl border-slate-300" type="text" name="driver" value="{{ old('driver', $connector?->driver) }}" required>
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">Mode</label>
        <select class="w-full rounded-xl border-slate-300" name="mode" required>
            @foreach(['manual', 'api', 'imap', 'excel', 'webhook'] as $mode)
                <option value="{{ $mode }}" @selected(old('mode', $connector?->mode ?? 'manual') === $mode)>{{ $mode }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">Status</label>
        <select class="w-full rounded-xl border-slate-300" name="status" required>
            @foreach(['disabled', 'active', 'error'] as $status)
                <option value="{{ $status }}" @selected(old('status', $connector?->status ?? 'disabled') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold">Endpoint URL</label>
        <input class="w-full rounded-xl border-slate-300" type="url" name="endpoint_url" value="{{ old('endpoint_url', $connector?->endpoint_url) }}">
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold">Config (JSON/String)</label>
        <textarea class="w-full rounded-xl border-slate-300" rows="4" name="encrypted_config">{{ old('encrypted_config', $connector?->encrypted_config) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold">Last Error</label>
        <textarea class="w-full rounded-xl border-slate-300" rows="2" name="last_error">{{ old('last_error', $connector?->last_error) }}</textarea>
    </div>
</div>
