<?php

namespace Thoughtco\EloquentFlatfile\Serializers;

use Statamic\Facades\Yaml;

class MarkdownSerializer extends Serializer
{
    public static function extension(): string
    {
        return '.md';
    }

    /**
     * @inheritDoc
     */
    public static function decode(string $contents): array
    {
        return Yaml::parse($contents);
    }

    /**
     * @inheritDoc
     */
    public static function encode(array $attributes): string
    {
        return Yaml::dump($attributes);
    }
}
