<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambassador extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'title', 'city', 'discipline', 'cluster', 'status', 'bio', 'whatsapp', 'instagram', 'skills', 'project_name', 'project_description', 'image', 'is_published'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    protected function casts(): array
    {
        return ['skills' => 'array', 'is_published' => 'boolean'];
    }
}
