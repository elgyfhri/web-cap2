<?php

namespace App\Http\Controllers;

use App\Models\Ambassador;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AmbassadorController extends Controller
{
    public function create()
    {
        return view('admin.ambassadors.form', ['item' => new Ambassador, 'categories' => Category::whereIn('name', ['Desain grafis', 'Programmer', 'Fotografi', 'IoT dan jaringan', 'Videografi', 'Digital marketing', 'Arsitektur dan interior', 'Data analyst', 'Otomotif'])->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'title' => 'nullable|string|max:255', 'discipline' => 'nullable|string|max:255', 'cluster' => 'required|string|in:Desain grafis,Programmer,Fotografi,IoT dan jaringan,Videografi,Digital marketing,Arsitektur dan interior,Data analyst,Otomotif', 'bio' => 'nullable|string', 'whatsapp' => 'nullable|string|max:30', 'instagram' => 'nullable|string|max:255', 'skills' => 'nullable|array', 'skills.*' => 'string|max:100', 'project_name' => 'nullable|string|max:255', 'project_description' => 'nullable|string', 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', 'is_published' => 'boolean']);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(5));
        $data['skills'] = $this->skills($request);
        $data['image'] = $request->hasFile('image') ? $request->file('image')->store('participants', 'public') : null;
        Ambassador::create($data);

        return redirect()->route('admin.ambassadors.index')->with('success', 'Ambassador dibuat.');
    }

    public function edit(Ambassador $ambassador)
    {
        return view('admin.ambassadors.form', ['item' => $ambassador, 'categories' => Category::whereIn('name', ['Desain grafis', 'Programmer', 'Fotografi', 'IoT dan jaringan', 'Videografi', 'Digital marketing', 'Arsitektur dan interior', 'Data analyst', 'Otomotif'])->orderBy('name')->get()]);
    }

    public function update(Request $request, Ambassador $ambassador)
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'title' => 'nullable|string|max:255', 'discipline' => 'nullable|string|max:255', 'cluster' => 'required|string|in:Desain grafis,Programmer,Fotografi,IoT dan jaringan,Videografi,Digital marketing,Arsitektur dan interior,Data analyst,Otomotif', 'bio' => 'nullable|string', 'whatsapp' => 'nullable|string|max:30', 'instagram' => 'nullable|string|max:255', 'skills' => 'nullable|array', 'skills.*' => 'string|max:100', 'project_name' => 'nullable|string|max:255', 'project_description' => 'nullable|string', 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', 'is_published' => 'boolean']);
        $data['skills'] = $this->skills($request);
        if ($request->hasFile('image')) {
            if ($ambassador->image) Storage::disk('public')->delete($ambassador->image);
            $data['image'] = $request->file('image')->store('participants', 'public');
        } else {
            unset($data['image']);
        }
        $ambassador->update($data);

        return redirect()->route('admin.ambassadors.index')->with('success', 'Ambassador diperbarui.');
    }

    private function skills(Request $request): array
    {
        return collect($request->input('skills', []))->flatMap(fn ($value) => explode(',', $value))->map(fn ($value) => trim($value))->filter()->values()->all();
    }

    public function destroy(Ambassador $ambassador)
    {
        $ambassador->delete();

        return back()->with('success', 'Ambassador dihapus.');
    }

    public function qr(Ambassador $ambassador)
    {
        abort_unless($ambassador->is_published, 404);

        return view('admin.ambassadors.qr', compact('ambassador'));
    }

    public function qrAll()
    {
        return view('admin.ambassadors.qr-all', ['items' => Ambassador::where('is_published', true)->orderBy('name')->get()]);
    }
}
