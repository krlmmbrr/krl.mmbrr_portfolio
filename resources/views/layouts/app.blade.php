<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>krl.mmbrr - ui/ux designer</title>
  <link rel="icon" href="{{ asset('assets/kaneki.png') }}">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            ink: '#111111',
            'surface-dark': '#1a1a1a'
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif']
          }
        }
      }
    }
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script>
    (function () {
      const saved = localStorage.getItem('theme');
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const useDark = saved ? saved === 'dark' : prefersDark;
      document.documentElement.classList.toggle('dark', useDark);
    })();
  </script>
</head>
<body class="antialiased bg-white text-gray-900 dark:bg-ink dark:text-white min-h-screen">
  @yield('content')

  <script type="module" src="{{ asset('emailjs/emailjs-contact.js') }}"></script>
  <script>window.portfolioAssetBase = @json(asset('assets')) + '/';</script>
  <script src="{{ asset('js/portfolio.js') }}"></script>
</body>
</html>
