<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thoughtco\EloquentFlatfile\Traits\FlatFile;

class Entry extends Model
{
    use HasFactory;

    // comment this out to use eloquent
    // would be nicer if we could set it in config/database.php, or it knew to use Stache from the connection?
    // that would mean hooking into bootSushi() or loading sushi dynamically
    use FlatFile;

    public function getSchema()
    {
        // we would load in blueprint(s) here and would need to merge in all handles
        // would also be nice to generate a migration file
        return [
            'id' => 'string',
            'title' => 'string',
            'collection' => 'string',
        ];
    }

    public function getTable()
    {
        // do we have an entry model per collection that we create on the fly?
        // at the moment this works
        return 'collections';
    }
}
