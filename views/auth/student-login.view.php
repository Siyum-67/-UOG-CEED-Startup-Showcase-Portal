<section class="bg-gray-50">
  <div class="mx-auto flex min-h-[80vh] max-w-6xl items-center justify-center px-4 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-lg">
      <div class="flex justify-center">
        <span class="rounded-full bg-[#FFD786] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#237227]">CEED Founders</span>
      </div>
      <h1 class="mt-4 text-center text-2xl font-bold text-[#237227]">Student Login</h1>
      <p class="mt-2 text-center text-sm text-gray-600">Access your startup submission and profile.</p>

      <?php if (!empty($error)): ?>
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
          <?php echo e($error); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
          <?php echo e($success); ?>
        </div>
      <?php endif; ?>

      <form class="mt-6 space-y-4" method="POST" action="">
        <?php if (function_exists('csrf_token')): ?> <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>"> <?php endif; ?>
        <!-- If you use $csrfToken instead: <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>"> -->

        <div>
          <label for="email" class="text-sm font-medium text-gray-700">Institutional Email</label>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="student@uog.edu.et"
            value="<?php if (!empty($_POST['email'])) echo e($_POST['email']); ?>"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]"
            required
          />
          <p class="mt-1 text-xs text-gray-500">Only @uog.edu.et emails are allowed.</p>
        </div>

        <div>
          <label for="password" class="text-sm font-medium text-gray-700">Password</label>
          <input
            type="password"
            name="password"
            id="password"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]"
            required
          />
        </div>

        <button type="submit" class="w-full rounded-lg bg-[#FFAA00] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e09a00]">
          Log In
        </button>
      </form>

      <div class="mt-6 border-t border-gray-200 pt-4">
        <p class="text-center text-sm text-gray-600">
          Don't have an account?
          <a href="index.php?<?php echo http_build_query(['route' => 'student/register']); ?>" class="font-medium text-[#519A66]">Register</a>
        </p>
        <p class="mt-2 text-center text-xs text-gray-500">
          <a href="index.php?<?php echo http_build_query(['route' => 'showcase']); ?>" class="font-medium text-[#237227] hover:text-[#519A66]">Back to Showcase</a>
        </p>
      </div>
    </div>
  </div>
</section>
