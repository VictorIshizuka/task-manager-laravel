<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\{Log, Storage};

class ProjectFile extends Model
{
    protected $fillable = [
        'project_id',
        'path',
        'original_name'
    ];

    protected static function booted()
    {
        /**
         * Quando deletar o registro do arquivo, também deleta o arquivo físico
         */
        static::deleting(function ($file) {
            try {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);

                    Log::info("Arquivo físico do ProjectFile {$file->id} deletado", [
                        'file_id' => $file->id,
                        'project_id' => $file->project_id,
                        'path' => $file->path,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("Erro ao deletar arquivo físico do ProjectFile {$file->id}: " . $e->getMessage());
            }
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
