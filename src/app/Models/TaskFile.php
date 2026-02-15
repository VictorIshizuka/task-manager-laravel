<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskFile extends Model
{
    protected $fillable = [
        'task_id',
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

                    Log::info("Arquivo físico do TaskFile {$file->id} deletado", [
                        'file_id' => $file->id,
                        'task_id' => $file->task_id,
                        'path' => $file->path,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("Erro ao deletar arquivo físico do TaskFile {$file->id}: " . $e->getMessage());
            }
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
