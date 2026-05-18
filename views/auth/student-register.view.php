<section class="bg-gray-50">
  <div class="mx-auto flex min-h-[80vh] max-w-6xl items-center justify-center px-4 py-12">
    <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg">
      <h1 class="text-center text-2xl font-bold text-[#237227]">Join CEED Founders</h1>
      <p class="mt-2 text-center text-sm text-gray-600">Register to showcase your startup.</p>

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

      <form class="mt-6 space-y-4" method="POST" action="index.php?<?php echo http_build_query(['route' => 'student/register']); ?>">
        <?php if (function_exists('csrf_token')): ?> <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>"> <?php endif; ?>
        <!-- If you use $csrfToken instead: <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>"> -->

        <div>
          <label for="full_name" class="text-sm font-medium text-gray-700">Full Name</label>
          <input type="text" name="full_name" id="full_name" value="<?php if (!empty($_POST['full_name'])) echo e($_POST['full_name']); ?>"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
        </div>

        <div>
          <label for="email" class="text-sm font-medium text-gray-700">Institutional Email</label>
          <input type="text" name="email" id="email" value="<?php if (!empty($_POST['email'])) echo e($_POST['email']); ?>"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
          <p class="mt-1 text-xs text-gray-500">Must use a valid @uog.edu.et email</p>
        </div>

        <div>
          <label for="password" class="text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" id="password"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
        </div>

        <div>
          <label for="confirm_password" class="text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
        </div>

        <button type="submit" class="w-full rounded-lg bg-[#FFAA00] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
          Create Account
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <a href="index.php?<?php echo http_build_query(['route' => 'student/login']); ?>" class="font-medium text-[#519A66]">Log In</a>
      </p>
    </div>
  </div>
</section>
