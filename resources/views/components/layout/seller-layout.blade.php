<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

  @props(['title' => null])

  <x-seo.meta :title="$title ?? 'Seller Studio — Elite Archive'"
              :description="'Seller studio for the Elite Archive marketplace.'"
              :index="false" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

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
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
  <div class="flex min-h-screen">

    <x-common.seller-sidebar />
    
    <div class="flex-1 flex flex-col min-w-0 md:pl-72">
      <x-common.seller-header />

      <main class="flex-1 overflow-auto">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
          {{ $slot }}
        </div>
      </main>
    </div>

  </div>
</body>
</html>
