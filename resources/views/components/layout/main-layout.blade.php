<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

  @props([
      'title' => null,
      'seoTitle' => null,
      'seoDescription' => null,
      'seoImage' => null,
      'seoType' => 'website',
      'jsonLd' => [],
  ])

  @php
    $routeName = request()->route()?->getName();
    $seoDefaults = [
        'home' => ['Rare & Antique Books — Elite Archive', 'Curated rare, antique and first-edition books, hand-selected by trusted independent booksellers.'],
        'detail' => [null, null],
        'cart' => ['Your Collection Bag — Elite Archive', 'Review the books in your collection bag before checking out.'],
        'checkout' => ['Checkout — Elite Archive', 'Complete your order and arrange secure payment.'],
        'checkout.success' => ['Order Registered — Elite Archive', 'Your order has been placed into our fulfillment queue.'],
        'orders.index' => ['Order Ledger — Elite Archive', 'View the history of your orders.'],
        'orders.show' => ['Order Detail — Elite Archive', 'Review the details and status of your order.'],
        'wishlist.index' => ['Wishlist — Elite Archive', 'Books you have saved for later.'],
        'about' => ['About — Elite Archive', 'The story behind our curated archive of rare books.'],
        'service' => ['Services — Elite Archive', 'Appraisal, sourcing and bespoke collection services.'],
        'contact' => ['Contact — Elite Archive', 'Get in touch with the Elite Archive team.'],
        'seller.register' => ['Seller Studio — Elite Archive', 'Apply to sell your rare books on Elite Archive.'],
        'dashboard' => ['Dashboard — Elite Archive', null],
    ];
    $routeDefaults = $seoDefaults[$routeName] ?? [null, null];
  @endphp

  <x-seo.meta :title="$seoTitle ?? $title ?? $routeDefaults[0]"
              :description="$seoDescription ?? $routeDefaults[1]"
              :image="$seoImage"
              :type="$seoType"
              :json-ld="$jsonLd" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN + Alpine.js -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            serif: ['"Playfair Display"', 'Georgia', 'serif'],
          },
          colors: {
            primary: '#141414',
            gold: '#d97706',
            accent: '#0066ff',
          }
        }
      }
    }
  </script>
</head>

<body class="font-sans bg-[#fafafa] text-slate-900 antialiased selection:bg-slate-900 selection:text-white">

<!-- ================= NAVBAR ================= -->
<x-common.header />

<!-- ================= MAIN CONTENT ================= -->
<main class="min-h-screen">
  {{ $slot }}
</main>

<!-- ================= FOOTER ================= -->
<x-common.footer />

</body>
</html>
