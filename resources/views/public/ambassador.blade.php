@extends('layouts.app')
@section('content')
<section class="bg-white border-b"><div class="max-w-[1360px] mx-auto px-6 py-4 text-sm text-slate-500"><a href="{{ route('home') }}">Beranda</a><span class="mx-2">›</span><a href="{{ route('ambassadors.index') }}">Direktori Peserta</a><span class="mx-2">›</span><b class="text-slate-900">{{ $ambassador->name }}</b></div></section>
<section class="max-w-[1360px] mx-auto px-5 sm:px-6 py-10 lg:py-14"><div class="grid lg:grid-cols-12 gap-4 lg:gap-10"><div class="lg:col-span-7 lg:col-start-6 order-1 lg:row-start-1"><span class="rounded-full bg-emerald-100 text-brand px-3 py-1 text-xs font-bold">{{ $ambassador->discipline }}</span><h1 class="text-4xl sm:text-5xl font-extrabold mt-4">{{ $ambassador->name }}</h1></div><aside class="lg:col-span-5 order-2 lg:order-none lg:col-start-1 lg:row-start-1"><div class="bg-white rounded-2xl p-3 shadow-xl"><div class="aspect-[4/5] rounded-xl bg-gradient-to-br from-emerald-100 to-slate-200 overflow-hidden">@if($ambassador->image)<img src="{{ str_starts_with($ambassador->image, 'http') ? $ambassador->image : asset('storage/'.$ambassador->image) }}" alt="{{ $ambassador->name }}" class="w-full h-full object-cover">@endif</div><div class="flex justify-between items-center p-4"><span class="font-bold text-brand">Peserta CAP-2</span><span class="rounded bg-emerald-100 text-brand px-2 py-1 text-xs font-bold">Aktif</span></div></div></aside><div class="lg:col-span-7 order-2 lg:order-none lg:col-start-6 lg:row-start-1 lg:mt-36"><div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm mt-2 lg:mt-0"><div class="flex items-center gap-2"><span class="text-brand text-2xl">“</span><h2 class="text-2xl font-bold">Visi &amp; Narasi Perjalanan</h2></div><p class="text-lg text-slate-600 leading-relaxed mt-2">{{ $ambassador->bio }}</p><div class="flex flex-wrap gap-3 mt-6"><a href="{{ route('ambassadors.qr.single', ['ambassador' => $ambassador->slug]) }}" class="relative z-10 inline-flex cursor-pointer items-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-bold text-white hover:bg-primary-container"><span class="material-symbols-outlined">qr_code_2</span>Lihat QR</a>@if($ambassador->whatsapp || $ambassador->instagram)@if($ambassador->whatsapp)<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ambassador->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="relative z-10 pointer-events-auto inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[#25D366] px-4 py-3 text-sm font-bold text-white"><span class="material-symbols-outlined">chat</span>WhatsApp</a>@endif @if($ambassador->instagram)<a href="{{ str_starts_with($ambassador->instagram, 'http') ? $ambassador->instagram : 'https://instagram.com/'.ltrim($ambassador->instagram, '@') }}" target="_blank" rel="noopener noreferrer" class="relative z-10 pointer-events-auto inline-flex cursor-pointer items-center gap-2 rounded-xl bg-gradient-to-r from-purple-600 via-pink-500 to-orange-400 px-4 py-3 text-sm font-bold text-white"><span class="material-symbols-outlined">photo_camera</span>Instagram</a>@endif</div>@endif</div></div></div></section>
<section class="bg-white"><style>[x-cloak]{display:none!important}.justified-gallery{display:flex;flex-wrap:wrap;gap:6px}.jg-item{display:block;width:100%;height:auto}</style><div class="max-w-[1360px] mx-auto px-5 sm:px-6 py-14"><span class="rounded-full bg-emerald-100 text-brand px-3 py-1 text-xs font-bold">Karya / Project</span><h2 class="text-4xl font-extrabold mt-4">Project {{ $ambassador->name }}</h2><div class="grid grid-cols-1 gap-8 mt-8">@forelse($ambassador->projects as $project)<article class="rounded-2xl bg-surface-container-low p-4 sm:p-5 overflow-hidden"><span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $project->type === 'Project Bersama' ? 'bg-indigo-50 text-indigo-700' : 'bg-emerald-50 text-brand' }}">{{ $project->type }}</span><h3 class="text-2xl font-bold mt-3">{{ $project->title }}</h3><p class="text-lg text-slate-600 mt-3">{{ $project->description }}</p>@if($project->link)<a href="{{ $project->link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 mt-5 rounded-xl bg-primary px-4 py-3 text-sm font-bold text-white hover:bg-primary-container"><span class="material-symbols-outlined text-lg">open_in_new</span>Lihat Project</a>@endif @if($project->images)<div class="mt-6" x-data="{ open:false, src:'', alt:'' }" x-effect="document.body.classList.toggle('overflow-hidden', open)" @keydown.escape.window="open=false">@php $docImages = collect($project->images)->map(function ($image) { $path = public_path('storage/'.$image); $size = is_file($path) ? @getimagesize($path) : null; return ['src' => asset('storage/'.$image), 'w' => $size[0] ?? 1600, 'h' => $size[1] ?? 1000]; }); @endphp<div class="justified-gallery -mx-4 sm:-mx-5" data-justified>@foreach($docImages as $doc)<img src="{{ $doc['src'] }}" alt="Dokumentasi {{ $project->title }}" loading="lazy" data-ratio="{{ $doc['w'] / $doc['h'] }}" @click="open=true; src=@js($doc['src']); alt=@js('Dokumentasi '.$project->title)" class="jg-item rounded-lg cursor-zoom-in">@endforeach</div><div x-cloak x-show="open" x-transition.opacity @click.self="open=false" role="dialog" aria-modal="true" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 p-4 sm:p-8"><button type="button" @click="open=false" aria-label="Tutup" class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"><span class="material-symbols-outlined">close</span></button><img :src="src" :alt="alt" @click.stop class="max-h-[90vh] max-w-full rounded-lg object-contain shadow-2xl"></div></div>@endif</article>@empty<div class="rounded-2xl bg-surface-container-low p-8 text-center text-slate-500">Project peserta belum dipublikasikan.</div>@endforelse</div></div></section>
<section class="w-full px-6 py-14"><div class="max-w-[1360px] mx-auto"><div class="bg-gradient-to-br from-primary via-primary-container to-tertiary text-on-primary rounded-3xl p-8 md:p-12 lg:p-16 shadow-xl"><div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center"><div class="lg:col-span-7"><span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-on-primary/10 text-secondary-fixed text-xs font-bold uppercase tracking-wider">Kemitraan Lisensi &amp; Akselerasi</span><h2 class="text-3xl lg:text-5xl font-bold tracking-tight mt-5">Tertarik Bermitra, Mendanai, atau Mengembangkan Inovasi {{ $ambassador->name }}?</h2><p class="text-base lg:text-lg text-primary-fixed-dim mt-4 leading-relaxed">Kami sangat terbuka untuk diskusi dan kolaborasi dalam mengembangkan project peserta.</p></div><div class="lg:col-span-5 bg-surface-container-lowest text-on-surface rounded-2xl p-6 shadow-xl"><h3 class="text-xl font-bold">Langkah Kolaborasi Cepat</h3><p class="text-sm text-on-surface-variant mt-2">Hubungi peserta untuk mendiskusikan ide dan peluang kolaborasi:</p><div x-data="{ open:false }" class="mt-5 relative"><button type="button" @click="open=!open" class="w-full px-5 py-3 rounded-xl bg-primary text-on-primary font-semibold flex items-center justify-center gap-2"><span class="material-symbols-outlined text-lg">forum</span>Ajak Diskusi Teknis &amp; Uji Coba</button><div x-show="open" x-transition class="flex items-center justify-center gap-4 mt-4"><span class="text-xs text-on-surface-variant">Pilih kanal:</span>@if($ambassador->whatsapp)<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ambassador->whatsapp) }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-lg hover:scale-110 transition"><span class="material-symbols-outlined text-2xl">chat</span></a>@endif @if($ambassador->instagram)<a href="{{ str_starts_with($ambassador->instagram, 'http') ? $ambassador->instagram : 'https://instagram.com/'.ltrim($ambassador->instagram, '@') }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 text-white flex items-center justify-center shadow-lg hover:scale-110 transition"><span class="material-symbols-outlined text-2xl">photo_camera</span></a>@endif @if(!$ambassador->whatsapp && !$ambassador->instagram)<span class="text-sm text-on-surface-variant">Kontak belum tersedia.</span>@endif</div></div></div></div></div></div></section>
<script>
(function () {
  var GAP = 6;
  function layout(container) {
    var items = Array.prototype.slice.call(container.querySelectorAll('.jg-item'));
    if (!items.length) return;
    var width = container.clientWidth;
    if (!width) return;
    var target = width < 640 ? 180 : (width < 1024 ? 240 : 320);
    var rows = [], row = [], sum = 0;
    items.forEach(function (el) {
      var a = parseFloat(el.dataset.ratio) || 1.5;
      if (row.length && (sum + a) * target + row.length * GAP > width) {
        rows.push({ items: row, sum: sum });
        row = []; sum = 0;
      }
      row.push(el); sum += a;
    });
    if (row.length) rows.push({ items: row, sum: sum });
    container.innerHTML = '';
    container.style.display = 'block';
    rows.forEach(function (r, ri) {
      var wrap = document.createElement('div');
      wrap.style.display = 'flex';
      wrap.style.gap = GAP + 'px';
      if (ri < rows.length - 1) wrap.style.marginBottom = GAP + 'px';
      var h = (width - GAP * (r.items.length - 1)) / r.sum;
      r.items.forEach(function (el) {
        var a = parseFloat(el.dataset.ratio) || 1.5;
        el.style.width = (h * a) + 'px';
        el.style.height = h + 'px';
        el.style.flex = '0 0 auto';
        wrap.appendChild(el);
      });
      container.appendChild(wrap);
    });
  }
  function layoutAll() {
    document.querySelectorAll('[data-justified]').forEach(layout);
  }
  var raf;
  window.addEventListener('resize', function () { cancelAnimationFrame(raf); raf = requestAnimationFrame(layoutAll); });
  document.addEventListener('DOMContentLoaded', layoutAll);
  window.addEventListener('load', layoutAll);
  if (document.readyState !== 'loading') layoutAll();
})();
</script>
@endsection