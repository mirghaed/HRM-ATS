<form method="post" action="{{ $action }}" class="grid gap-4">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <input class="rounded-xl border-slate-300" name="name" value="{{ old('name', $department?->name) }}" placeholder="نام دپارتمان" required>
    <input class="rounded-xl border-slate-300" name="slug" value="{{ old('slug', $department?->slug) }}" placeholder="slug" required>
    <textarea class="rounded-xl border-slate-300" name="description" placeholder="توضیحات">{{ old('description', $department?->description) }}</textarea>
    <select class="rounded-xl border-slate-300" name="manager_user_id"><option value="">بدون مدیر</option>@foreach($managers as $manager)<option value="{{ $manager->id }}" @selected(old('manager_user_id', $department?->manager_user_id)==$manager->id)>{{ $manager->name }}</option>@endforeach</select>
    <button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره</button>
</form>