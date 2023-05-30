<?php

namespace Thoughtco\EloquentFlatfile;

use Illuminate\Support\Facades\Storage;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    public function bootAddon()
    {
        app()->bind(Contracts\FlatFileContract::class, function () {
            return (new FlatFile())
                ->setStorage(Storage::build(base_path('content')));
        });
    }
}
