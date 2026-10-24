@extends('layouts.admin')
@section('content')
<style>@media print { @page { size: A4 portrait; margin: 12mm; } body { background: #fff !important; } body * { visibility: hidden !important; } .print-area, .print-area * { visibility: visible !important; } .print-area { position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; max-width: none !important; } .no-print { display: none !important; } .print-card { border: 1px solid #d1d5db !important; border-radius: 16px !important; box-shadow: none !important; margin: 0 auto !important; padding: 24px !important; width: 380px !important; break-inside: avoid !important; page-break-inside: avoid !important; } .print-card img { display: block !important; width: 280px !important; height: 280px !important; margin: 0 auto !important; } .print-card h2 { margin-top: 12px !important; } }</style><div class="max-w-xl mx-auto text-center print-area">
    <a href="{{ route('admin.ambassadors.index') }}" class="no-print text-sm font-bold text-[#006948]">Kembali ke peserta</a>
    <div class="print-card bg-white rounded-3xl border border-line p-8 mt-6">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(route('ambassadors.show', $ambassador)) }}" alt="QR {{ $ambassador->name }}" class="w-72 h-72 mx-auto">
        <h2 class="text-2xl font-extrabold mt-6">{{ $ambassador->name }}</h2>
        <p class="text-muted mt-2">Scan untuk membuka profil peserta</p>
        <div class="no-print flex justify-center gap-3 mt-5"><a href="{{ route('ambassadors.show', $ambassador) }}" target="_blank" class="inline-flex rounded-xl bg-[#006948] text-white px-5 py-3 text-sm font-bold">Buka profil</a><button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl border border-[#006948] text-[#006948] px-5 py-3 text-sm font-bold"><span class="material-symbols-outlined text-lg">print</span>Cetak QR</button></div>
    </div>
</div>
@endsection
