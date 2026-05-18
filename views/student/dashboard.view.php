<section class="bg-gray-50">
  <div class="mx-auto max-w-6xl px-4 py-12">
    <header class="mb-8">
      <p class="inline-flex items-center rounded-full bg-[#FFD786] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#237227]">Student Space</p>
      <h1 class="mt-3 text-3xl font-bold text-[#237227]">My Startups</h1>
      <p class="mt-2 text-sm text-gray-600">Review and manage the startups you have submitted to CEED.</p>
    </header>

    <div class="mb-8 flex flex-col items-start gap-6 rounded-xl bg-white p-6 shadow-lg md:flex-row md:items-center md:justify-between">
      <div class="flex items-center gap-6">
        <div class="h-24 w-24 overflow-hidden rounded-full bg-gray-100">
          <?php if (!empty($student->avatar_path)): ?>
            <img src="<?php echo e($student->avatar_path); ?>" alt="Profile avatar" class="h-full w-full object-cover" />
          <?php else: ?>
            <?php
              $nameSource = !empty($student->full_name) ? $student->full_name : $student->email;
              $parts = preg_split('/\s+/', trim((string) $nameSource));
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
          <h2 class="text-2xl font-bold text-[#237227]"><?php echo e($student->full_name ?? 'Student'); ?></h2>
          <p class="mt-1 text-sm text-gray-600"><?php echo e($student->department ?? 'Department not set'); ?></p>
          <p class="mt-1 text-sm text-gray-600">Year of Study: <?php echo e($student->year_of_study ?? 'N/A'); ?></p>
          <?php if (!empty($student->bio)): ?>
            <p class="mt-2 text-sm text-gray-600"><?php echo e($student->bio); ?></p>
          <?php endif; ?>
        </div>
      </div>
      <a href="index.php?<?php echo http_build_query(['route' => 'student/logout']); ?>" class="rounded-lg bg-[#FFAA00] px-5 py-2 text-sm font-semibold text-white shadow hover:opacity-90">Logout</a>
    </div>

    <?php if (!empty($success)): ?>
      <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <?php echo e($success); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <?php echo e($error); ?>
      </div>
    <?php endif; ?>

    <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
      <div class="border-b border-gray-100 px-6 py-4">
        <h2 class="text-lg font-semibold text-[#237227]">Your Startup Posts</h2>
      </div>

      <?php if (empty($startups)): ?>
        <div class="px-6 py-10 text-center text-sm text-gray-600">
          You have not submitted any startups yet.
        </div>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
              <tr>
                <th class="px-6 py-3">Title</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Stage</th>
                <th class="px-6 py-3">Created</th>
                <th class="px-6 py-3">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php foreach ($startups as $startup): ?>
                <tr>
                  <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($startup->name); ?></td>
                  <td class="px-6 py-4 text-gray-600"><?php echo e($categoryMap[$startup->category_id] ?? 'Uncategorized'); ?></td>
                  <td class="px-6 py-4 text-gray-600"><?php echo e($startup->stage); ?></td>
                  <td class="px-6 py-4 text-gray-600"><?php echo e(date('M j, Y', strtotime($startup->created_at))); ?></td>
                  <td class="px-6 py-4">
                    <div class="flex flex-wrap gap-2">
                      <a href="index.php?<?php echo http_build_query(['route' => 'startups/show', 'slug' => $startup->slug]); ?>" class="rounded-full bg-[#237227] px-3 py-1 text-xs font-semibold text-white">View</a>
                      <a href="index.php?<?php echo http_build_query(['route' => 'startups/edit', 'id' => $startup->id]); ?>" class="rounded-full border border-[#519A66] px-3 py-1 text-xs font-semibold text-[#519A66]">Edit</a>
                      <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'student/startups/delete']); ?>" onsubmit="return confirm('Delete this startup? This cannot be undone.');">
                        <input type="hidden" name="_csrf" value="<?php echo e(csrf_token()); ?>" />
                        <input type="hidden" name="id" value="<?php echo e($startup->id); ?>" />
                        <button type="submit" class="rounded-full border border-red-500 px-3 py-1 text-xs font-semibold text-red-600">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
