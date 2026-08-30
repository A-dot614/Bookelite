@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'canonical' => null,
    'jsonLd' => [],
    'index' => true,
])

@php
    $siteName = config('seo.site_name', config('app.name'));
    $title = $title ?? config('seo.default_title') ?? $siteName;
    $description = $description ?? config('seo.default_description');
    $canonical = $canonical ?? url()->current();
    $image = $image ?? config('seo.default_image');
    $robots = $index ? 'index,follow' : 'noindex,nofollow';
    $twitterHandle = config('seo.twitter_handle');
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $robots }}">
@if ($twitterHandle)
  <meta name="twitter:site" content="@{{ $twitterHandle }}">
@endif
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
@if ($image)
  <meta property="og:image" content="{{ $image }}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="{{ $image }}">
@else
  <meta name="twitter:card" content="summary">
@endif

@if (is_array($jsonLd) && count($jsonLd))
  <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@elseif (is_string($jsonLd) && $jsonLd !== '')
  <script type="application/ld+json">{!! $jsonLd !!}</script>
@endif