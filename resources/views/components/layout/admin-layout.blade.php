<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Elite Book Store Dashboard</title>
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
  <!-- No-JS styles: mobile sidebar toggle & simple chart styles -->
  <style>
    /* Mobile sidebar toggle: when the hidden checkbox is checked, show the mobile sidebar */
    #mobile-sidebar-toggle:checked ~ aside#dashboardSidebarMobile {
      transform: translateX(0);
    }

    /* Add subtle shadow when open */
    #mobile-sidebar-toggle:checked ~ aside#dashboardSidebarMobile {
      box-shadow: 0 20px 40px rgba(2,6,23,0.12);
    }

    /* Default state (kept in tailwind classes too) */
    aside#dashboardSidebarMobile {
      transform: translateX(-100%);
    }

    /* Simple sparkbar styles used by the sales chart (pure CSS heights) */
    .sparkbar { transition: height .45s cubic-bezier(.2,.9,.2,1); background: linear-gradient(180deg,#f59e0b,#fbbf24); border-top-left-radius:6px;border-top-right-radius:6px; }
  </style>
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-800">
  <div class="flex min-h-screen">

    <!-- Mobile sidebar toggle (CSS-only) -->
    <input id="mobile-sidebar-toggle" type="checkbox" class="hidden" aria-hidden="true">

    <!-- Sidebar (mobile toggled via #mobile-sidebar-toggle) -->
<x-common.admin-sidebar />

    <!-- Page content -->
    <div class="flex-1 flex flex-col">
      <!-- Top header -->
<x-common.admin-header/>

      <!-- Main area -->
      <main class="p-6 bg-gray-50 overflow-auto">
        <div class="max-w-7xl mx-auto">
          {{$slot}}
        </div>
      </main>
    </div>
  </div>

  <!-- No JavaScript: sidebar toggle and charts are implemented using CSS-only techniques -->
</body>
</html>
