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
        $data['image'] = $request->hasFile('image') ? $request->file('image')->store('projects', 'web_public') : null;
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
        if ($request->hasFile('image')) {
            if ($project->image) Storage::disk('web_public')->delete($project->image);
            $data['image'] = $request->file('image')->store('projects', 'web_public');
        }
        $ambassadors = $data['ambassador_ids'];
        unset($data['ambassador_ids']);
        $remainingImages = collect($project->images ?? [])->reject(function ($image) use ($request) {
            if (!in_array($image, $request->input('remove_images', []), true)) return false;
            Storage::disk('web_public')->delete($image);
            return true;
        })->values()->all();
        $newImages = collect($request->file('images', []))->map(fn ($file) => $file->store('projects/gallery', 'web_public'))->all();
        $data['images'] = array_values(array_merge($remainingImages, $newImages));
        $project->update($data);
        $project->ambassadors()->sync($ambassadors);
        return redirect()->route('admin.projects.index')->with('success', 'Project diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) Storage::disk('web_public')->delete($project->image);
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120','images' => 'nullable|array','images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120','remove_images' => 'nullable|array',
            'is_published' => 'boolean',
        ]);
        if ($data['type'] === 'Project Mandiri' && count($data['ambassador_ids']) !== 1) {
            abort(422, 'Project Mandiri harus memiliki tepat satu peserta.');
        }
        return $data;
    }
}
