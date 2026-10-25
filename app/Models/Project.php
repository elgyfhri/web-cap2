<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'description', 'link', 'images', 'is_published'];

    public function ambassadors()
    {
        return $this->belongsToMany(Ambassador::class);
    }

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'images' => 'array'];
    }
}
