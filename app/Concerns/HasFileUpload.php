<?php

namespace App\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasFileUpload
{
    use HasPathAttribute;

    /**
     * Boot Trait
     *
     * @return void
     */
    protected static function bootHasFileUpload(): void
    {
        static::updating(function ($model) {
            $model->handleFileDeletionOnUpdate();
        });

        static::forceDeleting(function ($model) {
            $model->handleFileDeletionOnForceDelete();
        });

    }

    /**
     * Handle file deletion on model update.
     *
     * @return void
     */
    protected function handleFileDeletionOnUpdate(): void
    {
        foreach ($this->getDirty() as $key => $value) {
            if ($this->isPathAttribute($key)) {
                $originalPath = $this->getOriginal($key);
                if ($originalPath && $originalPath !== $value) {
                    $this->deleteFile($originalPath);
                }
            }
        }
    }

    /**
     * Handle file deletion on force delete.
     *
     * @return void
     */
    protected function handleFileDeletionOnForceDelete(): void
    {
        foreach ($this->getAttributes() as $key => $value) {
            if ($value && $this->isPathAttribute($key)) {
                $this->deleteFile($value);
            }
        }
    }


    /**
     * Store the file
     *
     * @param UploadedFile $file
     * @return string
     */
    public function processFile(UploadedFile $file): string
    {
        return $file->store('', $this->fileDisk());

    }

    /**
     * Get the disk that file should be stored on.
     *
     * @return string
     */
    protected function fileDisk(): string
    {
        return 'public';
    }

    /**
     * Delete File
     *
     * @param string $path
     * @return void
     */
    public function deleteFile(string $path): void
    {
        if (!empty($path) && Storage::disk($this->fileDisk())->exists($path)) {
            Storage::disk($this->fileDisk())->delete($path);
        }
    }

}
