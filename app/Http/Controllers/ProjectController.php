<?php

namespace App\Http\Controllers;

use App\Models\Ambassador;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', ['items' => Project::with('ambassadors')->latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.projects.form', ['item' => new Project, 'ambassadors' => Ambassador::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['images'] = collect($request->file('images', []))->map(fn ($file) => $file->store('projects/gallery', 'web_public'))->all();
        $ambassadors = $data['ambassador_ids'];
        unset($data['ambassador_ids']);
        $project = Project::create($data);
        $project->ambassadors()->sync($ambassadors);
        return redirect()->route('admin.projects.index')->with('success', 'Project dibuat.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['item' => $project, 'ambassadors' => Ambassador::orderBy('name')->get()]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validated($request);
        $ambassadors = $data['ambassador_ids'];
        unset($data['ambassador_ids']);
        $newImages = collect($request->file('images', []))->map(fn ($file) => $file->store('projects/gallery', 'web_public'))->all();
        if ($newImages) {
            $data['images'] = array_values(array_merge($project->images ?? [], $newImages));
        }
        $project->update($data);
        $project->ambassadors()->sync($ambassadors);
        return redirect()->route('admin.projects.index')->with('success', 'Project diperbarui.');
    }

    public function destroyImage(Request $request, Project $project)
    {
        $image = $request->validate(['image' => 'required|string'])['image'];
        $images = collect($project->images ?? []);
        if (!$images->contains($image)) {
            abort(404);
        }
        Storage::disk('web_public')->delete($image);
        $project->update(['images' => $images->reject(fn ($item) => $item === $image)->values()->all()]);
        return back()->with('success', 'Foto project dihapus.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) Storage::disk('web_public')->delete($project->image);
        foreach ($project->images ?? [] as $image) Storage::disk('web_public')->delete($image);
        $project->delete();
        return back()->with('success', 'Project dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'ambassador_ids' => 'required|array|min:1','ambassador_ids.*' => 'exists:ambassadors,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:Project Mandiri,Project Bersama',
            'description' => 'nullable|string',
            'link' => 'nullable|url|max:2000',
            'images' => 'nullable|array','images.*' => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'is_published' => 'boolean',
        ]);
        if ($data['type'] === 'Project Mandiri' && count($data['ambassador_ids']) !== 1) {
            abort(422, 'Project Mandiri harus memiliki tepat satu peserta.');
        }
        return $data;
    }
}
