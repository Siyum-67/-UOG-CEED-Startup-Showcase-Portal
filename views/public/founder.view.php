<section class="bg-gray-50">
  <div class="mx-auto max-w-6xl px-4 py-12">
    <?php if (empty($founder)): ?>
      <div class="rounded-2xl bg-white p-10 text-center text-sm text-gray-600 shadow">
        Founder profile not found.
      </div>
    <?php else: ?>
      <div class="mb-10 flex flex-col items-start gap-6 rounded-xl bg-white p-6 shadow-lg md:flex-row md:items-center">
        <div class="h-24 w-24 overflow-hidden rounded-full bg-gray-100">
          <?php if (!empty($founder->avatar_path)): ?>
            <img src="<?php echo e($founder->avatar_path); ?>" alt="Founder avatar" class="h-full w-full object-cover" />
          <?php else: ?>
            <?php
              $nameSource = !empty($founder->full_name) ? $founder->full_name : $founder->email;
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
          <h1 class="text-2xl font-bold text-[#237227]">
            <?php echo e($founder->full_name ?? 'Founder'); ?>
          </h1>
          <p class="mt-1 text-sm text-gray-600"><?php echo e($founder->department ?? 'Department not set'); ?></p>
          <p class="mt-1 text-sm text-gray-600">Year of Study: <?php echo e($founder->year_of_study ?? 'N/A'); ?></p>
          <?php if (!empty($founder->bio)): ?>
            <p class="mt-2 text-sm text-gray-600"><?php echo e($founder->bio); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="rounded-xl bg-white p-6 shadow-lg">
        <h2 class="text-lg font-semibold text-[#237227]">Startups by this Founder</h2>
        <div class="mt-6">
          <?php if (empty($startups)): ?>
            <div class="rounded-2xl bg-gray-50 p-10 text-center text-sm text-gray-600">
              No startups posted yet.
            </div>
          <?php else: ?>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
              <?php foreach ($startups as $startup): ?>
                <article class="overflow-hidden rounded-2xl bg-white shadow">
                  <div class="h-40 bg-gray-200">
                    <?php if (!empty($coverMap[$startup->id])): ?>
                      <img src="<?php echo e($coverMap[$startup->id]); ?>" alt="<?php echo e($startup->name); ?> cover" class="h-40 w-full object-cover" />
                    <?php endif; ?>
                  </div>
                  <div class="p-5">
                    <h3 class="text-xl font-semibold text-primary-green"><?php echo e($startup->name); ?></h3>
                    <span class="mt-2 inline-block rounded-full bg-light-orange px-3 py-1 text-xs font-semibold text-gray-800">
                      <?php echo e($categoryMap[$startup->category_id] ?? 'Uncategorized'); ?>
                    </span>
                    <p class="mt-3 text-sm text-gray-600"><?php echo e($startup->short_pitch); ?></p>
                    <a href="index.php?<?php echo http_build_query(['route' => 'startups/show', 'slug' => $startup->slug]); ?>" class="mt-4 inline-flex w-full items-center justify-center rounded-full border border-light-green px-4 py-2 text-sm font-semibold text-light-green hover:bg-light-green hover:text-white">
                      View Profile
                    </a>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
