<section class="bg-primary-green">
  <div class="mx-auto max-w-6xl px-4 py-16">
    <h1 class="text-4xl font-bold text-white md:text-5xl">Discover UoG's Next Big Ideas</h1>
    <p class="mt-4 text-lg text-light-orange">A curated showcase of student-led ventures, bold experiments, and real-world impact.</p>
    <div class="mt-8 flex flex-wrap gap-3">
      <a href="index.php?<?php echo http_build_query(['route' => 'showcase']); ?>" class="rounded-full border border-white/40 px-4 py-2 text-sm font-medium text-white hover:bg-white/10">All</a>
      <?php foreach ($categories as $category): ?>
        <?php
          $isActive = !empty($activeCategory) && $activeCategory->id === $category->id;
          $buttonClass = $isActive
            ? 'rounded-full bg-light-orange px-4 py-2 text-sm font-semibold text-[#237227]'
            : 'rounded-full bg-light-green px-4 py-2 text-sm font-medium text-white hover:bg-[#237227]';
        ?>
        <a href="index.php?<?php echo http_build_query(['route' => 'showcase', 'category' => $category->slug]); ?>" class="<?php echo $buttonClass; ?>">
          <?php echo e($category->name); ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="bg-gray-50">
  <div class="mx-auto max-w-6xl px-4 py-12">
    <?php if (empty($startups)): ?>
      <div class="rounded-2xl bg-white p-10 text-center text-sm text-gray-600 shadow">
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
              <p class="mt-1 text-xs text-gray-500">
                Team:
                <a href="index.php?<?php echo http_build_query(['route' => 'founder/show', 'id' => $startup->owner_id]); ?>" class="text-[#519A66] hover:underline">
                  <?php echo e($startup->team); ?>
                </a>
              </p>
              <span class="mt-2 inline-block rounded-full bg-light-orange px-3 py-1 text-xs font-semibold text-gray-800">
                <?php echo e($categoryMap[$startup->category_id] ?? 'Uncategorized'); ?>
              </span>
              <p class="mt-3 text-sm text-gray-600"><?php echo e($startup->short_pitch); ?></p>
              <a href="index.php?<?php echo http_build_query(['route' => 'startups/show', 'slug' => $startup->slug]); ?>" class="mt-4 inline-flex w-full items-center justify-center rounded-full border border-light-green px-4 py-2 text-sm font-semibold text-light-green hover:bg-light-green hover:text-white">
                View Profile
              </a>
              <a href="index.php?<?php echo http_build_query(['route' => 'startups/show', 'slug' => $startup->slug]); ?>" class="mt-2 block text-center text-xs text-gray-500 hover:text-[#519A66]">Leave a comment</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
