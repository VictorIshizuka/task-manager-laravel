<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $fillable = [
        'project_id',
        'path',
        'original_name'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
