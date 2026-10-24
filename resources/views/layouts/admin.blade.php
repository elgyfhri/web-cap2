<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $title ?? 'CAP-2 Admin' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#006948','primary-container':'#00855d','secondary-fixed':'#6ffbbe','surface:'#faf8ff','surface-container':'#eaedff','surface-container-low':'#f2f3ff','surface-container-lowest':'#ffffff','on-surface':'#131b2e','on-surface-variant':'#3d4a42','outline':'#6d7a72','outline-variant':'#bccac0','line':'#bccac0','muted':'#6d7a72'}}}}</script>
<script>document.addEventListener('change',function(event){if(event.target.type !== 'file') return;const files=[...event.target.files];if(files.some(file=>file.size > 3 * 1024 * 1024)){alert('Ukuran gambar maksimal 3MB.');event.target.value='';}});</script>
</head>
<body x-data="{ open: false }" class="bg-surface text-on-surface antialiased" style="font-family:'Plus Jakarta Sans',sans-serif">
<div class="min-h-screen lg:flex">
<aside :class="open ? 'block' : 'hidden lg:flex'" class="w-full lg:w-64 lg:fixed lg:inset-y-0 z-30 bg-surface-container-lowest border-r border-outline-variant/40 flex flex-col">
<div class="h-20 px-6 flex items-center border-b border-outline-variant/40"><a href="{{ route('admin.dashboard') }}" class="flex flex-col"><span class="text-xl font-bold tracking-tight">CAP-2</span><span class="text-[11px] uppercase tracking-wider text-[#006948] font-bold">Talenta Bogor</span></a><span class="ml-auto rounded-full bg-secondary-fixed/50 px-2.5 py-1 text-[10px] uppercase tracking-wider text-[#006948] font-bold">Admin</span></div>
<div class="px-5 pt-7 pb-4"><p class="text-[11px] uppercase tracking-wider text-outline font-bold">Manajemen Konten</p></div>
<nav class="px-3 space-y-1 flex-1">@foreach([['Dashboard','dashboard','admin.dashboard'],['Peserta','groups','admin.ambassadors.index'],['Project','workspaces','admin.projects.index'],['Artikel','article','admin.articles.index'],['Cluster/Talent','category','admin.categories.index'],['Galeri','photo_library','admin.galleries.index']] as [$label,$icon,$route])<a href="{{ route($route) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs($route === 'admin.dashboard' ? $route : str_replace('.index', '.*', $route)) ? 'bg-secondary-fixed/40 text-[#006948]' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}"><span class="material-symbols-outlined text-[21px]">{{ $icon }}</span>{{ $label }}</a>@endforeach</nav>
<div class="p-4 border-t border-outline-variant/40 space-y-2"><a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined text-[20px]">open_in_new</span>Lihat website</a><form method="post" action="{{ route('logout') }}">@csrf<button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50"><span class="material-symbols-outlined text-[20px]">logout</span>Keluar</button></form></div>
</aside>
<main class="w-full lg:ml-64 min-h-screen"><header class="h-20 bg-surface-container-lowest/90 backdrop-blur-md border-b border-outline-variant/40 px-5 lg:px-10 flex items-center justify-between sticky top-0 z-20"><button type="button" @click="open = !open" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl text-on-surface-variant hover:bg-surface-container" aria-label="Buka menu"><span class="material-symbols-outlined" x-text="open ? 'close' : 'menu'"></span></button><div><p class="text-[11px] uppercase tracking-wider text-[#006948] font-bold">Champion Ambassador Project</p><h1 class="text-lg font-bold mt-0.5">Panel Administrasi</h1></div><div class="flex items-center gap-3"><div class="hidden sm:block text-right"><p class="text-sm font-bold">{{ auth()->user()->name }}</p><p class="text-xs text-on-surface-variant capitalize">{{ auth()->user()->role }}</p></div><div class="w-10 h-10 rounded-full bg-[#006948] text-white flex items-center justify-center font-bold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div></div></header><div class="p-5 lg:p-10">@yield('content')</div></main>
</div>
@if($errors->any())<script>alert(@json($errors->first()));</script>@endif
</body>
</html>