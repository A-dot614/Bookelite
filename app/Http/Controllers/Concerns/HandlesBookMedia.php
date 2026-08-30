<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Ecommerce;
use Illuminate\Http\Request;

trait HandlesBookMedia
{
    protected function storeCover(Request $request): string
    {
        $path = $request->file('cover')->store('covers', 'public');

        return asset('storage/'.$path);
    }

    protected function storeGallery(Request $request, Ecommerce $book): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $sort = (int) $book->images()->max('sort_order');

        foreach ($request->file('images', []) as $file) {
            $book->images()->create([
                'path' => $file->store('covers', 'public'),
                'alt_text' => $book->title,
                'sort_order' => ++$sort,
            ]);
        }
    }
}