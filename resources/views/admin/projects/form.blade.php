@extends('layouts.admin')
@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.projects.index') }}" class="text-[#006948] font-bold text-sm">← Kembali ke project</a>
    <h2 class="text-3xl font-extrabold mt-5">{{ $item->exists ? 'Edit Project' : 'Tambah Project' }}</h2>
    <p class="text-muted mt-2">Pilih tipe project terlebih dahulu, lalu tentukan peserta.</p>

    <form x-data="{ type: '{{ old('type', $item->type ?? '') }}', selected: {{ json_encode(old('ambassador_ids', $item->exists ? $item->ambassadors->pluck('id')->all() : [])) }} }" method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.projects.update', $item) : route('admin.projects.store') }}" class="bg-white rounded-2xl border border-line p-6 lg:p-8 mt-8 space-y-5">
        @csrf
        @if($item->exists) @method('put') @endif
        @if($errors->any())
            <div class="bg-red-50 text-red-700 rounded-xl px-4 py-3">{{ $errors->first() }}</div>
        @endif

        <label class="block">
            <span class="text-sm font-bold">Tipe Project</span>
            <select name="type" x-model="type" @change="if (type === 'Project Mandiri') selected = []" required class="w-full mt-2 rounded-xl border border-line bg-canvas px-4 py-3">
                <option value="">Pilih tipe project</option>
                <option value="Project Mandiri" @selected(old('type', $item->type) === 'Project Mandiri')>Project Mandiri</option>
                <option value="Project Bersama" @selected(old('type', $item->type) === 'Project Bersama')>Project Bersama</option>
            </select>
        </label>

        <div x-data="{ open:false }" class="relative">
            <span class="text-sm font-bold">Kategori Peserta</span>
            <button type="button" :disabled="!type" @click="open=!open" class="mt-2 disabled:cursor-not-allowed disabled:opacity-50 w-full flex items-center justify-between rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-[#006948]/20" :class="open ? 'border-emerald-600 ring-2 ring-emerald-100' : ''">
                <span x-text="selected.length ? selected.length + ' Terpilih' : 'Pilih Kategori Peserta'"></span>
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#6ffbbe]/50 text-[#006948]">
                    <span class="material-symbols-outlined text-lg" x-text="open ? 'expand_less' : 'expand_more'"></span>
                </span>
            </button>
            <div x-show="open" @click.outside="open=false" x-transition class="absolute left-0 right-0 z-30 mt-2 max-h-64 overflow-y-auto rounded-2xl border border-gray-200 bg-white p-2 shadow-xl">
                @foreach($ambassadors as $ambassador)
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-3 hover:bg-[#f2f3ff]">
                        <input type="checkbox" name="ambassador_ids[]" :disabled="!type" value="{{ $ambassador->id }}" x-model="selected" @change="if (type === 'Project Mandiri' && selected.length > 1) selected = [String($event.target.value)]" class="h-4 w-4 rounded border-gray-300 text-emerald-700 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">{{ $ambassador->name }}</span>
                    </label>
                @endforeach
            </div>
            <p class="mt-2 text-xs text-muted">Project Mandiri: satu peserta. Project Bersama: beberapa peserta.</p>
        </div>

        <label class="block">
            <span class="text-sm font-bold">Judul Project</span>
            <input name="title" value="{{ old('title', $item->title) }}" required class="w-full mt-2 rounded-xl border border-line bg-canvas px-4 py-3">
        </label>

        <label class="block">
            <span class="text-sm font-bold">Deskripsi</span>
            <textarea name="description" rows="6" class="w-full mt-2 rounded-xl border border-line bg-canvas px-4 py-3">{{ old('description', $item->description) }}</textarea>
        </label>

        <label class="block">
            <span class="text-sm font-bold">Link Project <span class="text-muted font-normal">(opsional)</span></span>
            <input type="url" name="link" value="{{ old('link', $item->link) }}" placeholder="https://..." class="w-full mt-2 rounded-xl border border-line bg-canvas px-4 py-3">
        </label>

        <div class="space-y-3">
            <span class="text-sm font-bold">Foto Project</span>
            <div x-data="{
                files: [],
                choose() {
                    this.$refs.input.click();
                },
                pick(event) {
                    Array.from(event.target.files).forEach((file) => {
                        this.files.push({ file, url: URL.createObjectURL(file) });
                    });
                    this.sync();
                },
                remove(index) {
                    URL.revokeObjectURL(this.files[index].url);
                    this.files.splice(index, 1);
                    this.sync();
                },
                sync() {
                    const transfer = new DataTransfer();
                    this.files.forEach((item) => transfer.items.add(item.file));
                    this.$refs.input.files = transfer.files;
                }
            }" class="space-y-3">
                <input x-ref="input" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="hidden" @change="pick($event)">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" @click="choose()" class="inline-flex items-center gap-2 rounded-xl bg-[#006948] px-4 py-3 text-sm font-bold text-white hover:bg-[#00855d]">
                        <span class="material-symbols-outlined text-lg">add_photo_alternate</span>
                        Pilih Foto
                    </button>
                    <span class="text-sm text-muted" x-text="files.length ? files.length + ' foto dipilih' : 'Belum ada foto dipilih'"></span>
                </div>
                <p class="text-xs text-muted">Bisa pilih beberapa foto sekaligus. Format JPG, PNG, WebP. Maksimal 3MB per gambar.</p>
                <div x-show="files.length" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <template x-for="(item, index) in files" :key="item.url">
                        <div class="relative">
                            <img :src="item.url" :alt="'Foto baru ' + (index + 1)" class="w-full h-32 rounded-xl object-cover">
                            <button type="button" @click="remove(index)" aria-label="Hapus foto" class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white shadow hover:bg-red-700">
                                <span class="material-symbols-outlined text-lg">close</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        @if($item->images)
            <div class="space-y-3">
                <span class="text-sm font-bold">Foto Project Saat Ini</span>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($item->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}" class="w-full h-32 rounded-xl object-cover">
                            <button type="submit" form="delete-project-image-{{ $loop->index }}" aria-label="Hapus foto" class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white shadow hover:bg-red-700">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <label class="flex gap-3 items-center rounded-xl bg-canvas px-4 py-3 font-semibold">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published))>
            Tampilkan di website
        </label>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.projects.index') }}" class="border border-line rounded-xl px-5 py-3 font-bold">Batal</a>
            <button type="submit" class="bg-[#006948] text-white rounded-xl px-5 py-3 font-bold">Simpan Project</button>
        </div>
    </form>

    @if($item->exists && $item->images)
        @foreach($item->images as $image)
            <form id="delete-project-image-{{ $loop->index }}" method="post" action="{{ route('admin.projects.images.destroy', $item) }}" onsubmit="return confirm('Hapus foto ini?')" class="hidden">
                @csrf
                @method('delete')
                <input type="hidden" name="image" value="{{ $image }}">
            </form>
        @endforeach
    @endif
</div>
@endsection
