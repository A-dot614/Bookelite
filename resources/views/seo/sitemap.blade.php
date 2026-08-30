<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($books as $book)
        <url>
            <loc>{{ route('detail', $book->slug) }}</loc>
            <lastmod>{{ $book->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>{{ $book->published_at ? '0.8' : '0.6' }}</priority>
        </url>
    @endforeach

    @foreach ($staticUrls as $url)
        <url>
            <loc>{{ $url['loc'] }}</loc>
            <changefreq>{{ $url['changefreq'] }}</changefreq>
            <priority>{{ $url['priority'] }}</priority>
        </url>
    @endforeach
</urlset>