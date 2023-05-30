<?php

namespace Thoughtco\EloquentFlatfile\Traits;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;
use Thoughtco\EloquentFlatfile\Contracts\FlatFileContract;
use Thoughtco\EloquentFlatfile\Serializers\MarkdownSerializer;

trait FlatFile
{
    use Sushi;

    public static function bootFlatFile(): void
    {
        static::saved(static fn (Model $model) => self::flatFile()->save($model));
        static::deleted(static fn (Model $model) => self::flatFile()->delete($model));
    }

    public function initializeFlatFile()
    {
        $this->incrementing = false;
        $this->keyType = 'string';
        $this->timestamps = false;
    }

    public function getRows(): array
    {
        return self::flatFile()->all($this);
    }

    public function usesTimestamps(): bool
    {
        return $this->timestamps ?? false;
    }

    private static function flatFile(): FlatFileContract
    {
        return resolve(FlatFileContract::class)
            ->setSerializer(static::$flatFileSerializer ?? MarkdownSerializer::class);
    }
}
