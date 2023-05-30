<?php

namespace Thoughtco\EloquentFlatfile\Contracts;

use Thoughtco\EloquentFlatfile\Serializers\Serializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;

interface FlatFileContract
{
    public function setSerializer(Serializer|string $serializer): self;

    public function setStorage(FilesystemAdapter $storage): self;

    public function all(Model $model): array;

    public function save(Model $model): bool;

    public function delete(Model $model): bool;
}