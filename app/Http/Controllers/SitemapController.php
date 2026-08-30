<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $books = Ecommerce::published()
            ->select(['id', 'slug', 'updated_at', 'published_at'])
            ->orderBy('published_at')
            ->get();

        $staticUrls = [
            ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => route('service'), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => route('cart.index'), 'changefreq' => 'weekly', 'priority' => '0.3'],
        ];

        $xml = view('seo.sitemap', compact('books', 'staticUrls'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n"
            ."Disallow: /admin\n"
            ."Disallow: /seller\n"
            ."Disallow: /orders\n"
            ."Disallow: /wishlist\n"
            ."Disallow: /checkout\n"
            ."Disallow: /account\n"
            ."Disallow: /cart\n"
            ."Disallow: /login\n"
            ."Disallow: /register\n"
            ."Disallow: /forgot-password\n"
            ."Disallow: /reset-password\n"
            ."Disallow: /verify-email\n"
            ."Disallow: /confirm-password\n"
            ."Disallow: /dashboard\n"
            ."Sitemap: ".route('seo.sitemap')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}