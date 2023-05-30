<?php

namespace Thoughtco\EloquentFlatfile\Resolvers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PathResolver
{
    private function __construct(
        private readonly string $absolute,
        private readonly string $relative,
        private readonly string $filename
    ) {
    }

    public function __toString(): string
    {
        return $this->getAbsolutePathname();
    }

    public static function make(Model $model, string $extension): self
    {
        $basename = ($model->collection ?? '');
        if ($model->id) {
            $basename .= '/'.$model->id;
        }

        $filename = $basename !== '' ? $basename.$extension : '';

        return new self(base_path('content'), $model->getTable(), $filename);
    }

    public function getAbsolutePath(): string
    {
        return $this->absolute.'/'.$this->relative;
    }

    public function getRelativePath(): string
    {
        return $this->relative;
    }

    public function getAbsolutePathname(): string
    {
        return rtrim($this->absolute.'/'.$this->relative.'/'.$this->filename, '/');
    }

    public function getRelativePathname(): string
    {
        return $this->relative.'/'.$this->filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
