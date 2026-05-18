<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Startup Showcase Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary-green': '#237227',
            'light-green': '#519A66',
            'vibrant-orange': '#FFAA00',
            'light-orange': '#FFD786'
          }
        }
      }
    };
  </script>
  <style>
    body { font-family: 'Ubuntu', sans-serif; }
  </style>
</head>
<body class="bg-white text-gray-900">
  <header class="sticky top-0 z-50 bg-white shadow-sm">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
      <div class="flex items-center gap-3">
        <img src="./assets/gondar_university_logo.png" alt="UoG Logo" class="h-10 w-auto" />
        <img src="./assets/ceed_logo.png" alt="CEED Logo" class="h-10 w-auto" />
      </div>
      <nav class="flex items-center gap-6 text-sm font-medium">
        <a href="index.php?<?php echo http_build_query(['route' => 'showcase']); ?>" class="text-gray-700 hover:text-primary-green">Home</a>
        <a href="index.php?<?php echo http_build_query(['route' => 'showcase']); ?>" class="text-gray-700 hover:text-primary-green">Categories</a>
        <a href="index.php?<?php echo http_build_query(['route' => 'student/submit']); ?>" class="rounded-full bg-vibrant-orange px-4 py-2 text-white shadow hover:opacity-90">Submit Startup</a>
        <?php $isStudentLoggedIn = !empty($_SESSION['founderUserId']); ?>
        <?php if ($isStudentLoggedIn): ?>
          <?php
            $displayName = trim((string) ($_SESSION['founderName'] ?? ''));
            $displayEmail = (string) ($_SESSION['founderEmail'] ?? '');
            $firstName = $displayName !== '' ? explode(' ', $displayName)[0] : '';
            if ($firstName === '' && $displayEmail !== '') {
                $firstName = explode('@', $displayEmail)[0];
            }
            if ($firstName === '') {
                $firstName = 'Student';
            }
            $avatarPath = (string) ($_SESSION['founderAvatar'] ?? '');
          ?>
          <div class="relative group">
            <button type="button" class="flex items-center gap-3 rounded-full border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm">
              <span>Welcome, <?php echo e($firstName); ?></span>
              <span class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-gray-100 text-xs font-semibold text-[#519A66]">
                <?php if (!empty($avatarPath)): ?>
                  <img src="<?php echo e($avatarPath); ?>" alt="Avatar" class="h-full w-full object-cover" />
                <?php else: ?>
                  <?php echo e(strtoupper(substr($firstName, 0, 1))); ?>
                <?php endif; ?>
              </span>
            </button>
            <div class="absolute right-0 mt-2 hidden w-48 rounded-xl border border-gray-100 bg-white p-2 shadow-lg group-hover:block">
              <a href="index.php?<?php echo http_build_query(['route' => 'student/dashboard']); ?>" class="block rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">My Dashboard</a>
              <a href="index.php?<?php echo http_build_query(['route' => 'student/profile']); ?>" class="block rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit Profile</a>
              <a href="index.php?<?php echo http_build_query(['route' => 'student/logout']); ?>" class="block rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
            </div>
          </div>
        <?php else: ?>
          <a href="index.php?<?php echo http_build_query(['route' => 'student/login']); ?>" class="text-gray-700 hover:text-primary-green">Login</a>
          <a href="index.php?<?php echo http_build_query(['route' => 'student/register']); ?>" class="text-gray-700 hover:text-primary-green">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main>
    <?php echo $contents ?? ''; ?>
  </main>

  <footer class="bg-primary-green text-white">
    <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 py-6 md:flex-row">
      <p class="text-sm">© 2026 UoG CEED Startup Showcase. All rights reserved.</p>
      <div class="flex gap-4 text-sm">
        <a href="index.php?<?php echo http_build_query(['route' => 'student/login']); ?>" class="hover:text-light-orange">Student Login</a>
        <a href="index.php?<?php echo http_build_query(['route' => 'admin/login']); ?>" class="hover:text-light-orange">Admin Login</a>
      </div>
    </div>
  </footer>
</body>
</html>
