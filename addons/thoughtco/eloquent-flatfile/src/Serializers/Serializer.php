<?php

namespace Thoughtco\EloquentFlatfile\Serializers;

abstract class Serializer
{
    abstract public static function extension(): string;

    abstract public static function decode(string $contents): array;

    abstract public static function encode(array $attributes): string;
}