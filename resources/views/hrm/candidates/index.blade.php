@extends('layouts.hrm', ['title' => 'کارجویان'])
@section('content')
<form class="mb-4" method="get"><input class="w-full rounded-xl border-slate-300" name="q" value="{{ request('q') }}" placeholder="جستجو"></form>
<div class="card overflow-hidden"><table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">نام</th><th class="px-4 py-3 text-right">موبایل</th><th class="px-4 py-3 text-right">ایمیل</th><th class="px-4 py-3 text-right">عملیات</th></tr></thead><tbody>@foreach($candidates as $candidate)<tr class="border-t"><td class="px-4 py-3">{{ $candidate->full_name }}</td><td class="px-4 py-3">{{ $candidate->mobile }}</td><td class="px-4 py-3">{{ $candidate->email }}</td><td class="px-4 py-3"><a class="text-cyan-700" href="{{ route('hrm.candidates.show', $candidate) }}">مشاهده</a></td></tr>@endforeach</tbody></table></div>
<div class="mt-4">{{ $candidates->links() }}</div>
@endsection