<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120']);

        return response()->json(['url' => asset('storage/'.$request->file('image')->store('articles', 'web_public'))]);
    }

    public function create()
    {
        return view('admin.articles.form', ['item' => new Article]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'excerpt' => 'nullable|string|max:500', 'body' => 'nullable|string', 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', 'is_published' => 'boolean', 'published_at' => 'nullable|date']);
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;
        $data['image'] = $request->hasFile('image') ? $request->file('image')->store('articles/covers', 'web_public') : null;
        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel dibuat.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', ['item' => $article]);
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'excerpt' => 'nullable|string|max:500', 'body' => 'nullable|string', 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', 'is_published' => 'boolean', 'published_at' => 'nullable|date']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? ($article->published_at ?? now()) : null;
        if ($request->hasFile('image')) {
            if ($article->image && !str_starts_with($article->image, 'http')) Storage::disk('web_public')->delete($article->image);
            $data['image'] = $request->file('image')->store('articles/covers', 'web_public');
        } else {
            unset($data['image']);
        }
        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return back()->with('success', 'Artikel dihapus.');
    }
}
