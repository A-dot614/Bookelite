<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />

  @props(['title' => null])

  <x-seo.meta :title="$title ?? 'Book Store Admin — Elite Archive'"
              :description="'Administrative dashboard for Elite Archive.'"
              :index="false" />

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#111827',
            accent: '#f59e0b'
          }
        }
      }
    }
  </script>
  <style>
    #mobile-sidebar-toggle:checked ~ aside#dashboardSidebarMobile {
      transform: translateX(0);
      box-shadow: 0 20px 40px rgba(2,6,23,0.12);
    }

    aside#dashboardSidebarMobile {
      transform: translateX(-100%);
    }

    #mobile-sidebar-toggle:checked ~ label#dashboardSidebarBackdrop {
      display: block;
    }

    @media (min-width: 768px) {
      aside#dashboardSidebarMobile {
        transform: translateX(0);
      }
    }
  </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
  <div class="flex min-h-screen">

    <input id="mobile-sidebar-toggle" type="checkbox" class="hidden" aria-hidden="true">
     <!-- #region -->
     <x-common.admin-sidebar />
     <label id="dashboardSidebarBackdrop" for="mobile-sidebar-toggle" class="fixed inset-0 z-[45] hidden bg-slate-950/30 md:hidden" aria-label="Close menu"></label>
    
    <div class="flex-1 flex flex-col min-w-0 md:pl-72">
      <x-common.admin-header/>

      <main class="flex-1 overflow-auto">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
          {{$slot}}
        </div>
      </main>
    </div>
  </div>
</body>
</html>
