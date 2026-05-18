<div class="min-h-screen bg-gray-100 font-[Ubuntu]">
    <aside class="fixed left-0 top-0 flex h-full w-64 flex-col justify-between bg-[#237227] px-6 py-6 text-white">
        <div>
            <h1 class="text-xl font-bold">CEED Admin</h1>
            <nav class="mt-8 space-y-2 text-sm">
                <a href="index.php?<?php echo http_build_query(['route' => 'admin/startups']); ?>" class="block rounded-lg px-3 py-2 hover:bg-[#519A66]">Startups</a>
                <a href="index.php?<?php echo http_build_query(['route' => 'admin/users']); ?>" class="block rounded-lg px-3 py-2 hover:bg-[#519A66]">Users</a>
                <a href="index.php?<?php echo http_build_query(['route' => 'admin/comments']); ?>" class="block rounded-lg bg-[#519A66] px-3 py-2">Comments</a>
            </nav>
        </div>
        <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/logout']); ?>">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
            <button type="submit" class="w-full rounded-lg bg-[#FFAA00] px-3 py-2 text-sm font-semibold text-white">Logout</button>
        </form>
    </aside>

    <main class="ml-64 min-h-screen bg-gray-100 px-8 py-8">
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-[#237227]">Comment Moderation</h2>
        </header>

        <div class="overflow-x-auto rounded-xl bg-white shadow">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Startup</th>
                        <th class="px-6 py-3">Author</th>
                        <th class="px-6 py-3">Comment</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if (empty($comments)): ?>
                        <tr>
                            <td class="px-6 py-4 text-gray-500" colspan="5">No comments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo e($comment['id']); ?></td>
                                <td class="px-6 py-4"><?php echo e($comment['startup_name']); ?></td>
                                <td class="px-6 py-4"><?php echo e($comment['author_name']); ?></td>
                                <td class="px-6 py-4"><?php echo e($comment['body']); ?></td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/comments/delete']); ?>">
                                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                                        <input type="hidden" name="id" value="<?php echo e($comment['id']); ?>" />
                                        <button type="submit" class="rounded bg-red-600 px-3 py-1 text-sm text-white">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
