@extends('layouts.admin')
@section('content')
<style>@media print { @page { size: A4 portrait; margin: 10mm; } body * { visibility: hidden !important; } .qr-print-area, .qr-print-area * { visibility: visible !important; } .qr-print-area { position: absolute !important; inset: 0 !important; width: 100% !important; padding: 0 !important; } .no-print { display: none !important; } .qr-grid { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 8mm !important; } .qr-item { border: 1px solid #ddd !important; padding: 4mm !important; break-inside: avoid !important; page-break-inside: avoid !important; } .qr-item img { width: 100% !important; height: auto !important; } }</style>
<div class="no-print flex items-center justify-between gap-4 mb-8">
    <div><a href="{{ route('admin.ambassadors.index') }}" class="text-sm font-bold text-[#006948]">Kembali ke peserta</a><h2 class="text-3xl font-extrabold mt-4">QR Peserta</h2><p class="text-muted mt-2">Cetak atau simpan QR untuk dibagikan.</p></div>
    <button onclick="window.print()" class="rounded-xl bg-[#006948] text-white px-5 py-3 text-sm font-bold">Cetak semua</button>
</div>
<div class="qr-print-area qr-grid grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
@foreach($items as $item)
    <article class="qr-item bg-white rounded-2xl border border-line p-4 text-center break-inside-avoid">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(route('ambassadors.show', $item)) }}" alt="QR {{ $item->name }}" class="w-full aspect-square">
        <h3 class="font-bold text-sm mt-3">{{ $item->name }}</h3>
        <p class="text-xs text-muted mt-1">{{ $item->cluster }}</p>
    </article>
@endforeach
</div>
@endsection
