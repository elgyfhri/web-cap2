@extends('layouts.admin')
@section('content')
<div class="max-w-4xl mx-auto">
<a href="{{ route('admin.articles.index') }}" class="inline-flex items-center gap-1 text-sm text-[#006948] font-bold"><span class="material-symbols-outlined text-sm">arrow_back</span>Kembali ke artikel</a>
<div class="mt-6"><p class="text-sm text-[#006948] font-bold">Content editor</p><h2 class="text-3xl font-extrabold tracking-tight mt-1">{{ $item->exists ? 'Edit Artikel' : 'Tambah Artikel' }}</h2><p class="text-muted mt-2">Buat dokumentasi riset dan kegiatan CAP-2.</p></div>
<form id="article-form" method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.articles.update', $item) : route('admin.articles.store') }}" class="bg-white rounded-2xl border border-line p-6 lg:p-8 mt-8 space-y-5">
@csrf @if($item->exists) @method('put') @endif
@if($errors->any())<div class="rounded-xl bg-red-50 text-red-700 px-4 py-3 text-sm font-semibold">{{ $errors->first() }}</div>@endif
<label class="block"><span class="text-sm font-bold">Judul artikel</span><input name="title" value="{{ old('title', $item->title) }}" required class="w-full border border-line rounded-xl px-4 py-3 mt-2 bg-canvas focus:border-[#006948] outline-none"></label>
<label class="block"><span class="text-sm font-bold">Ringkasan</span><textarea name="excerpt" rows="3" class="w-full border border-line rounded-xl px-4 py-3 mt-2 bg-canvas focus:border-[#006948] outline-none">{{ old('excerpt', $item->excerpt) }}</textarea></label>
<label class="block"><span class="text-sm font-bold">Gambar sampul</span><input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full border border-line rounded-xl px-4 py-3 mt-2 bg-canvas focus:border-[#006948] outline-none"><p class="text-xs text-muted mt-2">Format JPG, PNG, WebP. Maksimal 3MB.</p>@if($item->image)<p class="text-xs text-muted mt-2">Gambar sampul saat ini tersimpan. Upload baru untuk menggantinya.</p>@endif</label>
<div><span class="text-sm font-bold">Isi artikel</span><div id="article-editor" class="mt-2 bg-canvas border border-line rounded-xl">{!! old('body', $item->body) !!}</div><textarea id="article-body" name="body" class="hidden">{{ old('body', $item->body) }}</textarea></div>
<label class="flex items-center gap-3 rounded-xl bg-canvas px-4 py-3 text-sm font-semibold"><input type="hidden" name="is_published" value="0"><input type="checkbox" name="is_published" value="1" @checked((bool) old('is_published', $item->is_published)) class="accent-primary"> Publikasikan artikel</label>
<div class="flex justify-end gap-3"><a href="{{ route('admin.articles.index') }}" class="rounded-xl border border-line px-5 py-3 text-sm font-bold">Batal</a><button class="inline-flex items-center gap-2 rounded-xl bg-[#006948] text-white px-5 py-3 text-sm font-bold"><span class="material-symbols-outlined text-lg">save</span>Simpan Artikel</button></div>
</form></div>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>#article-editor .ql-editor{min-height:20rem;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;line-height:1.7}#article-editor .ql-toolbar{background:#fff;border-color:#bccac0;border-radius:.75rem .75rem 0 0}#article-editor .ql-container{border-color:#bccac0;border-radius:0 0 .75rem .75rem}#article-editor .ql-picker-options{z-index:20}</style>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = document.getElementById('article-editor');
    const body = document.getElementById('article-body');
    const toolbar = [[{ header: [1, 2, 3, false] }], ['bold', 'italic', 'underline', 'strike'], [{ list: 'ordered' }, { list: 'bullet' }], ['blockquote', 'link', 'image'], ['clean']];
    const quill = new Quill(editor, { theme: 'snow', modules: { toolbar } });
    const toolbarModule = quill.getModule('toolbar');
    toolbarModule.addHandler('image', function () {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/jpeg,image/png,image/webp';
        input.click();
        input.addEventListener('change', async function () {
            if (!input.files.length) return;
            const data = new FormData();
            data.append('image', input.files[0]);
            const response = await fetch('{{ route('admin.articles.upload-image') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: data });
            if (!response.ok) return alert('Upload gambar gagal.');
            const result = await response.json();
            const range = quill.getSelection(true) || { index: quill.getLength() };
            quill.insertEmbed(range.index, 'image', result.url, 'user');
            quill.setSelection(range.index + 1, 0, 'silent');
        });
    });
    document.getElementById('article-form').addEventListener('submit', function () { body.value = quill.root.innerHTML; });
});
</script>
@endsection