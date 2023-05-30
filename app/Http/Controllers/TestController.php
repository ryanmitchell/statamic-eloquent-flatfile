<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        //dd(Entry::all());
        //dd(Entry::where('collection', 'pages')->get());

        $entry = Entry::make();
        $entry->collection = 'pages';
        $entry->title = 'Hi Jason';
        $entry->save();

        //dd(
    }
}
