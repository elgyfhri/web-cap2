<?php

namespace App\Http\Controllers;

use App\Models\Ambassador;
use App\Models\Article;
use App\Models\Project;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $published = Ambassador::where('is_published', true);
        return view('public.home', [
            'ambassadors' => (clone $published)->orderBy('id')->take(8)->get(),
            'participantCount' => (clone $published)->count(),
            'clusters' => (clone $published)->whereNotNull('cluster')->select('cluster')->distinct()->orderBy('cluster')->pluck('cluster'),
            'clusterCounts' => (clone $published)->whereNotNull('cluster')->selectRaw('cluster, count(*) as total')->groupBy('cluster')->pluck('total', 'cluster'),
            'projectMandiriCount' => Project::where('is_published', true)->where('type', 'Project Mandiri')->count(),
            'projectBersamaCount' => Project::where('is_published', true)->where('type', 'Project Bersama')->count(),
            'articles' => Article::with('category')->where('is_published', true)->latest('published_at')->latest('id')->take(8)->get(),
        ]);
    }

    public function ambassadors(?string $cluster = null)
    {
        $query = Ambassador::where('is_published', true);
        $clusterName = null;
        if ($cluster !== null) {
            $clusterName = Ambassador::where('is_published', true)->whereNotNull('cluster')->get()->first(fn ($item) => Str::slug($item->cluster) === $cluster)?->cluster;
            $query->where('cluster', $clusterName ?? $cluster);
        }
        $search = trim((string) request('q'));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $like = '%'.$search.'%';
                $q->where('name', 'like', $like)->orWhere('title', 'like', $like)->orWhere('cluster', 'like', $like)->orWhere('discipline', 'like', $like)->orWhere('city', 'like', $like)->orWhere('project_name', 'like', $like)->orWhere('project_description', 'like', $like);
            });
        }
        if (request('status')) $query->where('status', request('status'));
        $clusters = Ambassador::where('is_published', true)->whereNotNull('cluster')->select('cluster')->distinct()->orderBy('cluster')->pluck('cluster');
        $statuses = Ambassador::where('is_published', true)->whereNotNull('status')->select('status')->distinct()->orderBy('status')->pluck('status');

        return view('public.ambassadors', ['ambassadors' => $query->orderByRaw("CASE WHEN LOWER(name) = 'muhamad elgy fahri hidayat' THEN 0 ELSE 1 END")->orderBy('id')->get(), 'selectedCluster' => $clusterName ?? '', 'clusters' => $clusters, 'statuses' => $statuses]);
    }

    public function cluster(string $cluster)
    {
        return $this->ambassadors($cluster);
    }

    public function qr(Ambassador $ambassador)
    {
        abort_unless($ambassador->is_published, 404);

        return view('public.qr', compact('ambassador'));
    }

    public function qrAll()
    {
        return view('public.qr-all', [
            'items' => Ambassador::where('is_published', true)->orderBy('name')->get(),
        ]);
    }

    public function articles()
    {
        $query = Article::with('category')->where('is_published', true);
        if ($search = trim((string) request('q'))) {
            $query->where('title', 'like', '%'.$search.'%');
        }

        return view('public.articles', ['articles' => $query->latest('published_at')->paginate(9)->withQueryString()]);
    }

    public function ambassador(Ambassador $ambassador)
    {
        abort_unless($ambassador->is_published, 404);

        $ambassador->load(['projects' => fn ($query) => $query->where('is_published', true)->orderByRaw("CASE WHEN type = 'Project Mandiri' THEN 0 ELSE 1 END")->latest()]);

        return view('public.ambassador', compact('ambassador'));
    }

    public function article(Article $article)
    {
        abort_unless($article->is_published, 404);

        return view('public.article', compact('article'));
    }
}
