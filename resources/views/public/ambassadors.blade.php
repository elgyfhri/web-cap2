@extends('layouts.app')
@section('content')
<section class="relative overflow-hidden"><div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[340px] rounded-full bg-gradient-to-r from-emerald-100 via-green-50 to-teal-100 blur-3xl pointer-events-none"></div><div class="relative max-w-[1360px] mx-auto px-5 lg:px-10 pt-12 pb-8"><div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-7 border-b border-slate-200"><div class="max-w-3xl"><span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 text-emerald-800 px-3 py-1 text-sm font-bold"><span class="w-2 h-2 rounded-full bg-brand animate-pulse"></span>Katalog Lengkap Resmi · Kohort 2025</span><h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight mt-4">Direktori <span class="text-brand">{{ $ambassadors->count() }} Peserta</span> &amp; Portofolio Project</h1><p class="text-lg text-slate-600 mt-4 leading-relaxed">Eksplorasi profil lengkap, spesialisasi teknis, dan project nyata talenta muda Bogor.</p></div><div class="bg-white rounded-2xl p-4 shadow-sm border shrink-0"><p class="text-xs uppercase tracking-wider text-slate-500">Total Peserta</p><b class="text-2xl">{{ $ambassadors->count() }} <span class="text-sm text-brand">Siap Kolaborasi</span></b></div></div><div class="mt-8 space-y-3"><h2 class="text-2xl font-bold text-on-surface">9 Cluster/talent</h2><div class="flex flex-col md:flex-row items-center gap-3 w-full"><div class="flex-1 relative w-full"><span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">search</span><input id="participantSearch" value="{{ request('q') }}" placeholder="Cari nama talenta, proyek, stack teknologi, atau kota..." class="w-full h-11 pl-11 pr-4 bg-white rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm text-gray-700"></div><a href="{{ route('ambassadors.qr') }}" class="shrink-0 h-11 inline-flex items-center gap-2 rounded-2xl bg-primary text-white px-5 py-3 text-sm font-bold shadow-md hover:bg-primary-container"><span class="material-symbols-outlined text-lg">qr_code_2</span>Lihat QR Semua</a></div><div class="flex flex-row flex-nowrap items-center gap-2 overflow-x-auto overflow-y-hidden py-2 no-scrollbar touch-pan-x" id="disciplineFilters"><button type="button" data-cluster="" class="discipline-filter active shrink-0 rounded-full px-4 py-2 text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap bg-emerald-800 text-white">Semua <span class="bg-emerald-700/60 text-white rounded-full px-2 py-0.5 text-xs">{{ $ambassadors->count() }}</span></button>@foreach($clusters as $cluster)<button type="button" data-cluster="{{ $cluster }}" class="discipline-filter shrink-0 rounded-full px-4 py-2 text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">{{ $cluster }} <span class="bg-indigo-50 text-indigo-600 rounded-full px-2 py-0.5 text-xs font-semibold">{{ $ambassadors->where('cluster', $cluster)->count() }}</span></button>@endforeach</div><p id="liveResultCount" class="text-sm text-gray-500">Menampilkan <b class="text-gray-900">{{ $ambassadors->count() }}</b> peserta</p></div></div></section>
<section class="max-w-[1360px] mx-auto px-5 lg:px-10 pb-16"><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">@forelse($ambassadors as $item)<article class="participant-card bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col gap-4 h-full" data-search="{{ strtolower($item->name.' '.$item->cluster.' '.$item->project_name.' '.$item->project_description.' '.implode(' ', $item->skills ?? []).' '.$item->city) }}" data-cluster="{{ $item->cluster }}" data-status="{{ $item->status }}"><div class="flex-1"><div class="flex flex-row items-start gap-4 mb-4"><div class="w-40 h-52 rounded-2xl bg-emerald-100 flex-shrink-0 overflow-hidden">@if($item->image)<img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">@else<div class="w-full h-full flex items-center justify-center text-brand"><span class="material-symbols-outlined text-4xl">person</span></div>@endif</div><div class="flex flex-col items-start gap-1 pt-6 min-w-0"><span class="px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-full mb-1">{{ $item->cluster }}</span><h2 class="text-lg font-bold text-gray-900 leading-snug">{{ $item->name }}</h2><p class="text-sm text-gray-400 font-normal">{{ $item->city }}</p></div></div><div class="flex flex-col"><span class="inline-flex w-fit px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-full mb-2">{{ $item->status }}</span><h3 class="text-lg font-bold text-gray-900 line-clamp-2">{{ $item->project_name }}</h3><p class="text-sm text-gray-600 line-clamp-3 mt-1">{{ $item->project_description }}</p></div></div><div class="border-t border-gray-100 pt-4 flex items-center gap-3 mt-auto"><a href="{{ route('ambassadors.show', $item) }}" class="inline-flex items-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white hover:bg-primary-container">Lihat Detail</a><a href="{{ route('ambassadors.qr.single', $item) }}" class="inline-flex items-center gap-1 rounded-xl bg-black px-4 py-2 text-sm font-bold text-white hover:bg-gray-800" aria-label="QR Code {{ $item->name }}"><span class="material-symbols-outlined text-base">qr_code_2</span>QR</a></div></article>@empty<div class="md:col-span-3 text-center py-16 text-slate-500">Data peserta tidak ditemukan.</div>@endforelse</div></section>
<style>#disciplineFilters{display:flex;flex-wrap:nowrap;overflow-x:auto;overflow-y:hidden;max-width:100%;touch-action:pan-x;cursor:grab;scrollbar-width:thin;scrollbar-color:#006948 #f2f3ff}#disciplineFilters:active{cursor:grabbing}#disciplineFilters::-webkit-scrollbar{height:6px}#disciplineFilters::-webkit-scrollbar-track{background:#f2f3ff;border-radius:999px}#disciplineFilters::-webkit-scrollbar-thumb{background:#006948;border-radius:999px}#disciplineFilters button{flex:0 0 auto}</style>
<script>
const search = document.getElementById('participantSearch');
const count = document.getElementById('liveResultCount');
const cards = [...document.querySelectorAll('.participant-card')];
const filters = [...document.querySelectorAll('.discipline-filter')];
let cluster = @json($selectedCluster ?? '') || sessionStorage.getItem('selectedDiscipline') || '';
sessionStorage.removeItem('selectedDiscipline');

function filterParticipants() {
    const query = search.value.toLowerCase().trim();
    let visible = 0;
    cards.forEach(card => {
        const matchText = !query || card.dataset.search.includes(query);
        const matchCluster = !cluster || card.dataset.cluster === cluster;
        const show = matchText && matchCluster;
        card.classList.toggle('hidden', !show);
        if (show) visible++;
    });
    count.innerHTML = `Menampilkan <b class="text-gray-900">${visible}</b> peserta`;
}

search.addEventListener('input', filterParticipants);

filters.forEach(button => button.addEventListener('click', event => {
    event.preventDefault();
    event.stopPropagation();
    cluster = button.dataset.cluster;
    filters.forEach(item => item.className = 'discipline-filter shrink-0 rounded-full px-4 py-2 text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap bg-white border border-gray-200 text-gray-700 hover:bg-gray-50');
    button.className = 'discipline-filter active rounded-full px-4 py-2 text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap bg-emerald-800 text-white';
    filterParticipants();
}));

const selectedFilter = filters.find(button => button.dataset.cluster === cluster);
if (selectedFilter) selectedFilter.click();
else {
    cluster = '';
    filterParticipants();
}
</script>
@endsection