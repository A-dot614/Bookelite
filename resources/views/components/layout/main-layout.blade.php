<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Elite Book Store</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            poppins: ['Poppins', 'sans-serif']
          },
          colors: {
            primary: '#0f172a',
            gold: '#facc15'
          }
        }
      }
    }
  </script>
</head>

<body class="font-poppins bg-gray-100 text-gray-800">

<!-- ================= NAVBAR ================= -->
<x-common.header />
<!-- ================= BOOK SECTION ================= -->
    {{ $slot }}
<!-- ================= FOOTER ================= -->
<x-common.footer />
</body>
</html>
