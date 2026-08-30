<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Elite Archive — Read with Distinction' }}</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

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
