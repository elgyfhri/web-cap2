<?php

namespace App\Http\Controllers;

use App\Models\Ambassador;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', ['ambassadors' => Ambassador::count(), 'articles' => Article::count(), 'categories' => Category::count(), 'galleries' => Gallery::count(), 'projects' => Project::count()]);
    }

    public function ambassadors()
    {
        $search = trim((string) request('q'));
        $query = Ambassador::latest();

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $like = '%'.$search.'%';
                $query->where('name', 'like', $like)
                    ->orWhere('cluster', 'like', $like)
                    ->orWhere('discipline', 'like', $like)
                    ->orWhere('city', 'like', $like)
                    ->orWhere('project_name', 'like', $like);
            });
        }

        return view('admin.ambassadors.index', ['items' => $query->paginate(15)->withQueryString(), 'search' => $search]);
    }

    public function articles()
    {
        return view('admin.articles.index', ['items' => Article::with('category')->latest('published_at')->latest('id')->paginate(15)]);
    }

    public function categories()
    {
        return view('admin.categories.index', ['items' => Category::whereHas('ambassadors')->withCount('ambassadors')->orderBy('name')->paginate(15)]);
    }

    public function galleries()
    {
        return view('admin.galleries.index', ['items' => Gallery::latest()->paginate(15)]);
    }

    public function bulkDelete(Request $request, string $type)
    {
        $model = match ($type) {
            'ambassadors' => Ambassador::class,
            'articles' => Article::class,
            'projects' => Project::class,
            'galleries' => Gallery::class,
            default => abort(404),
        };

        $items = $model::whereIn('id', $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'])->get();
        foreach ($items as $item) {
            foreach (['image', 'images'] as $field) {
                $files = is_array($item->{$field}) ? $item->{$field} : [$item->{$field}];
                foreach (array_filter($files) as $file) if (!str_starts_with($file, 'http')) Storage::disk('public')->delete($file);
            }
            $item->delete();
        }

        return back()->with('success', $items->count().' data dihapus.');
    }
}
