<?php
$coverImage = null;
if (!empty($media)) {
        foreach ($media as $item) {
                if ($item->type === 'image') {
                        $coverImage = $item->path;
                        break;
                }
        }
}
?>

<section class="bg-gray-50">
    <div class="mx-auto max-w-6xl px-4 py-12">
        <header class="mb-8">
            <?php if (!empty($coverImage)): ?>
                <div class="overflow-hidden rounded-3xl shadow-lg">
                    <img src="<?php echo e($coverImage); ?>" alt="<?php echo e($startup->name); ?> cover" class="h-72 w-full object-cover md:h-80" />
                </div>
            <?php else: ?>
                <div class="flex h-56 items-center justify-center rounded-3xl bg-white shadow-lg">
                    <span class="text-sm font-semibold text-gray-500">No cover image uploaded yet.</span>
                </div>
            <?php endif; ?>
        </header>

        <div class="rounded-xl bg-white p-8 shadow-lg">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-[#237227]"><?php echo e($startup->name); ?></h1>
                    <div class="mt-4 flex flex-wrap gap-2 text-sm">
                        <span class="rounded-full bg-[#FFD786] px-3 py-1 font-semibold text-[#237227]"><?php echo !empty($category) ? e($category->name) : 'Uncategorized'; ?></span>
                        <span class="rounded-full bg-[#FFD786] px-3 py-1 font-semibold text-[#237227]">Stage: <?php echo e($startup->stage); ?></span>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-sm text-gray-600">
                <strong class="font-medium text-gray-700">Team:</strong>
                <a href="index.php?<?php echo http_build_query(['route' => 'founder/show', 'id' => $startup->owner_id]); ?>" class="text-[#519A66] hover:underline">
                    <?php echo e($startup->team); ?>
                </a>
            </div>

            <p class="mt-4 text-lg italic text-gray-700"><?php echo e($startup->short_pitch); ?></p>

            <div class="mt-6 space-y-4 text-sm leading-7 text-gray-600">
                <?php echo nl2br(e($startup->description)); ?>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[2fr,1fr]">
                <div>
                    <?php if (!empty($media)): ?>
                        <h2 class="text-lg font-semibold text-[#237227]">Media</h2>
                        <div class="mt-4 flex flex-wrap justify-center gap-6">
                            <?php foreach ($media as $item): ?>
                                <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 shadow-sm">
                                    <?php if ($item->type === 'image'): ?>
                                        <img src="<?php echo e($item->path); ?>" alt="Startup media" class="h-80 w-full object-cover md:h-96" />
                                    <?php else: ?>
                                        <video controls class="h-80 w-full object-cover md:h-96">
                                            <source src="<?php echo e($item->path); ?>" />
                                        </video>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($startup->drive_link)): ?>
                        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5">
                            <h3 class="text-sm font-semibold text-gray-700">Pitch Deck</h3>
                            <a href="<?php echo e($startup->drive_link); ?>" target="_blank" rel="noopener" class="mt-3 inline-flex w-full items-center justify-center rounded-lg bg-[#FFAA00] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#e09a00]">
                                View on Google Drive
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <section class="mt-8">
            <div class="mb-6 rounded-xl bg-white p-6 shadow-lg">
                <h2 class="text-lg font-semibold text-[#237227]">Comments</h2>

                <?php if (!empty($commentSuccess)): ?>
                    <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        <?php echo e($commentSuccess); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($commentErrors)): ?>
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc space-y-1 pl-5">
                            <?php foreach ($commentErrors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'comments/store']); ?>" class="mt-5 grid gap-4">
                    <input type="hidden" name="_csrf" value="<?php echo e(csrf_token()); ?>" />
                    <input type="hidden" name="startup_id" value="<?php echo e($startup->id); ?>" />
                    <input type="hidden" name="startup_slug" value="<?php echo e($startup->slug); ?>" />

                    <div>
                        <label for="author_name" class="text-sm font-medium text-gray-700">Your Name</label>
                        <input type="text" name="author_name" id="author_name" value="<?php echo e($commentOld['author_name'] ?? $prefillName ?? ''); ?>"
                            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
                    </div>
                    <div>
                        <label for="body" class="text-sm font-medium text-gray-700">Your Comment</label>
                        <textarea name="body" id="body" rows="3"
                            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]"><?php echo e($commentOld['body'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <button type="submit" class="rounded-lg bg-[#519A66] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">Submit Comment</button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <?php if (empty($comments)): ?>
                    <div class="rounded-xl bg-white p-6 text-sm text-gray-600 shadow">No comments yet. Be the first to comment.</div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="rounded-xl bg-white p-5 shadow">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="font-semibold text-[#237227]"><?php echo e($comment->author_name); ?></span>
                                <span class="text-xs text-gray-500"><?php echo e($comment->created_at); ?></span>
                            </div>
                            <p class="mt-2 text-sm text-gray-600"><?php echo nl2br(e($comment->body)); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</section>
