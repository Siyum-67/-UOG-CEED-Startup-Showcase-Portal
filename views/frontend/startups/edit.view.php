<?php
$nameValue = $_POST['name'] ?? $startup->name;
$categoryValue = $_POST['category_id'] ?? $startup->category_id;
$stageValue = $_POST['stage'] ?? $startup->stage;
$teamValue = $_POST['team'] ?? $startup->team;
$shortPitchValue = $_POST['short_pitch'] ?? $startup->short_pitch;
$descriptionValue = $_POST['description'] ?? $startup->description;
$driveLinkValue = $_POST['drive_link'] ?? $startup->drive_link;

$currentMediaNote = 'Current file: none uploaded yet.';
if (!empty($media)) {
        foreach ($media as $item) {
                if (!empty($item->path)) {
                        $currentMediaNote = 'Current file: ' . basename($item->path);
                        break;
                }
        }
}
?>

<section class="bg-gray-50">
    <div class="mx-auto max-w-3xl px-4 py-12">
        <div class="rounded-xl bg-white p-8 shadow-md">
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-[#237227]">Edit Your Startup</h1>
                <p class="mt-2 text-sm text-gray-600">Update your startup profile for the CEED showcase.</p>
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

            <form method="POST" enctype="multipart/form-data" action="index.php?<?php echo http_build_query(['route' => 'startups/edit', 'id' => $startup->id]); ?>" class="space-y-8">
                <input type="hidden" name="_csrf" value="<?php echo e(csrf_token()); ?>" />

                <section class="rounded-lg border border-gray-200 p-5">
                    <h2 class="text-lg font-semibold text-[#519A66]">Basic Info</h2>
                    <div class="mt-4 grid gap-4">
                        <div>
                            <label for="startup_name" class="text-sm font-medium text-gray-700">Startup Name</label>
                            <input type="text" id="startup_name" name="name" value="<?php echo e($nameValue); ?>"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
                        </div>

                        <div>
                            <label for="category" class="text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category_id"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]">
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo ((string) $categoryValue === (string) $category->id) ? 'selected' : ''; ?>>
                                        <?php echo e($category->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="stage" class="text-sm font-medium text-gray-700">Development Stage</label>
                            <select id="stage" name="stage"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]">
                                <option value="">Select a stage</option>
                                <option value="idea" <?php echo ((string) $stageValue === 'idea') ? 'selected' : ''; ?>>Idea</option>
                                <option value="prototype" <?php echo ((string) $stageValue === 'prototype') ? 'selected' : ''; ?>>Prototype</option>
                                <option value="early-revenue" <?php echo ((string) $stageValue === 'early-revenue') ? 'selected' : ''; ?>>Early Revenue</option>
                                <option value="scaling" <?php echo ((string) $stageValue === 'scaling') ? 'selected' : ''; ?>>Scaling</option>
                            </select>
                        </div>

                        <div>
                            <label for="team" class="text-sm font-medium text-gray-700">Team Members</label>
                            <input type="text" id="team" name="team" placeholder="John Doe, Jane Smith" value="<?php echo e($teamValue); ?>"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-gray-200 p-5">
                    <h2 class="text-lg font-semibold text-[#519A66]">The Pitch</h2>
                    <div class="mt-4 grid gap-4">
                        <div>
                            <label for="short_pitch" class="text-sm font-medium text-gray-700">Short Pitch</label>
                            <textarea id="short_pitch" name="short_pitch" maxlength="150" rows="2"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]"><?php echo e($shortPitchValue); ?></textarea>
                            <p class="mt-1 text-xs text-gray-500">A one-sentence summary of what you do.</p>
                        </div>

                        <div>
                            <label for="description" class="text-sm font-medium text-gray-700">Full Description</label>
                            <textarea id="description" name="description" rows="5"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]"><?php echo e($descriptionValue); ?></textarea>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-gray-200 p-5">
                    <h2 class="text-lg font-semibold text-[#519A66]">Media &amp; Links</h2>
                    <div class="mt-4 grid gap-4">
                        <div>
                            <label for="cover_image" class="text-sm font-medium text-gray-700">Cover Image</label>
                            <input type="file" id="cover_image" name="media[]" accept="image/*"
                                class="mt-1 w-full rounded-lg border border-gray-300 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#519A66] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:opacity-90" />
                            <p class="mt-1 text-xs text-gray-500"><?php echo e($currentMediaNote); ?></p>
                        </div>

                        <div>
                            <label for="demo_video" class="text-sm font-medium text-gray-700">Demo Video (Optional)</label>
                            <input type="file" id="demo_video" name="media[]" accept="video/*"
                                class="mt-1 w-full rounded-lg border border-gray-300 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#519A66] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:opacity-90" />
                            <p class="mt-1 text-xs text-gray-500"><?php echo e($currentMediaNote); ?></p>
                        </div>

                        <div>
                            <label for="pitch_deck" class="text-sm font-medium text-gray-700">Pitch Deck Link</label>
                            <input type="url" id="pitch_deck" name="drive_link" placeholder="https://drive.google.com/..." value="<?php echo e($driveLinkValue); ?>"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#519A66] focus:ring-[#519A66]" />
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit" class="w-full rounded-lg bg-[#FFAA00] px-6 py-3 text-sm font-semibold text-white hover:opacity-90 sm:w-auto">
                        Update Startup Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
