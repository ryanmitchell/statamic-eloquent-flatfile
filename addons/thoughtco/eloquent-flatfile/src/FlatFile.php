<?php

namespace Thoughtco\EloquentFlatfile;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Statamic\Facades\Stache;
use Thoughtco\EloquentFlatfile\Resolvers\PathResolver;
use Thoughtco\EloquentFlatfile\Serializers\Serializer;

class FlatFile implements Contracts\FlatFileContract
{
    private Serializer|string $serializer;

    private FilesystemAdapter|Filesystem $storage;

    public function setStorage(FilesystemAdapter|Filesystem $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function setSerializer(Serializer|string $serializer): self
    {
        throw_if(
            is_subclass_of($serializer, Serializer::class) === false,
            InvalidArgumentException::class,
            sprintf('[%s] must be a subclass of %s', $serializer, Serializer::class),
        );

        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function all(Model $model): array
    {
        $relativePath = $this->getPathResolver($model)->getRelativePath();

        $files = $this->storage->allFiles($relativePath);

        $data = collect($files)
            ->map(function ($path) use ($model, $relativePath) {
                if (! Str::endsWith($path, '.md')) {
                    return;
                }

                $contents = $this->serializer::decode($this->storage->get($path));
                $contents = collect($contents)->only(array_keys($model->getSchema() ?? [])); // this means we only return blueprint fields

                // this logic is entry specific right now, needs to be modified
                $contents['collection'] = Str::of($path)->after($relativePath.'/')->before('/');

                if ($contents['collection']) {
                    if ($locale = Str::of($path)->after($relativePath.'/'.$contents['collection'].'/')) {
                        if (str_contains($locale, '/')) {
                            $contents['locale'] = Str::before($locale, '/');
                        }
                    }
                }


                return $contents;
            })
            ->filter()
            ->values()
            ->toArray();

        return $data;
    }

    public function save(Model $model): bool
    {
        if (! ($model->id ?? false)) {
            $model->id = Stache::generateId();
        }

        return $this->storage->put(
            $this->getPathResolver($model)->getRelativePathname(),
            $this->serializer::encode($model->getAttributes()),
        );
    }

    public function delete(Model $model): bool
    {
        if (method_exists($model, 'trashed') && $model->{'trashed'}()) {
            return $model->save();
        }

        $pathResolver = $this->getPathResolver($model);

        if (count($this->storage->allFiles($pathResolver->getRelativePath())) === 1) {
            return $this->storage->deleteDirectory($pathResolver->getRelativePath());
        }

        return $this->storage->delete($pathResolver->getRelativePathname());
    }

    private function getPathResolver(Model $model): PathResolver
    {
        return PathResolver::make(
            $model,
            $this->serializer::extension(),
        );
    }
}
