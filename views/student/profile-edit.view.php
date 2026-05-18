<section class="bg-gray-50">
  <div class="mx-auto max-w-4xl px-4 py-12">
    <header class="mb-8">
      <p class="inline-flex items-center rounded-full bg-[#FFD786] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#237227]">Student Profile</p>
      <h1 class="mt-3 text-3xl font-bold text-[#237227]">My Profile</h1>
      <p class="mt-2 text-sm text-gray-600">Keep your information up to date for mentors and reviewers.</p>
    </header>

    <?php if (!empty($errors)): ?>
      <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc space-y-1 pl-5">
          <?php foreach ($errors as $message): ?>
            <li><?php echo e($message); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <?php echo e($success); ?>
      </div>
    <?php endif; ?>

    <div class="rounded-2xl bg-white p-8 shadow-lg">
      <form method="POST" enctype="multipart/form-data" action="index.php?<?php echo http_build_query(['route' => 'student/profile/update']); ?>" class="space-y-6">
        <?php if (function_exists('csrf_token')): ?> <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>"> <?php endif; ?>

        <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-center">
          <div class="h-24 w-24 overflow-hidden rounded-full bg-gray-100">
            <?php if (!empty($student->avatar_path)): ?>
              <img src="<?php echo e($student->avatar_path); ?>" alt="Profile avatar" class="h-full w-full object-cover" />
            <?php else: ?>
              <?php
                $initialSource = !empty($student->full_name) ? $student->full_name : $student->email;
                $parts = preg_split('/\s+/', trim($initialSource));
                $initials = '';
                foreach ($parts as $part) {
                  if ($part !== '') {
                    $initials .= strtoupper(substr($part, 0, 1));
                  }
                  if (strlen($initials) >= 2) {
                    break;
                  }
                }
                if ($initials === '') {
                  $initials = 'CE';
                }
              ?>
              <div class="flex h-full w-full items-center justify-center text-xl font-semibold text-[#519A66]">
                <?php echo e($initials); ?>
              </div>
            <?php endif; ?>
          </div>
          <div>
            <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-[#519A66] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:opacity-90" />
            <p class="mt-1 text-xs text-gray-500">JPG, PNG, or WEBP. Max 2MB.</p>
          </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
          <div>
            <label for="full_name" class="text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo e($formData['full_name'] ?? ''); ?>" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
          </div>

          <div>
            <label for="year_of_study" class="text-sm font-medium text-gray-700">Year of Study</label>
            <select id="year_of_study" name="year_of_study" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]">
              <option value="">Select</option>
              <?php for ($year = 1; $year <= 5; $year++): ?>
                <option value="<?php echo $year; ?>" <?php echo ((string) ($formData['year_of_study'] ?? '') === (string) $year) ? 'selected' : ''; ?>><?php echo $year; ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="md:col-span-2">
            <label for="department" class="text-sm font-medium text-gray-700">Department / Program</label>
            <input type="text" id="department" name="department" value="<?php echo e($formData['department'] ?? ''); ?>" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
          </div>

          <div class="md:col-span-2">
            <label for="bio" class="text-sm font-medium text-gray-700">Bio</label>
            <textarea id="bio" name="bio" rows="4" maxlength="500" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" ><?php echo e($formData['bio'] ?? ''); ?></textarea>
            <p class="mt-1 text-xs text-gray-500">Tell mentors about you (max 500 characters).</p>
          </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4">
          <a href="index.php?<?php echo http_build_query(['route' => 'student/dashboard']); ?>" class="text-sm font-medium text-[#519A66]">Back to Dashboard</a>
          <button type="submit" class="rounded-lg bg-[#FFAA00] px-5 py-2 text-sm font-semibold text-white transition hover:bg-[#e09a00]">Save Profile</button>
        </div>
      </form>
    </div>
  </div>
</section>
