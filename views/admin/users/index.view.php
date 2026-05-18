<div class="min-h-screen bg-gray-100 font-[Ubuntu]">
    <aside class="fixed left-0 top-0 flex h-full w-64 flex-col justify-between bg-[#237227] px-6 py-6 text-white">
        <div>
            <h1 class="text-xl font-bold">CEED Admin</h1>
            <nav class="mt-8 space-y-2 text-sm">
                <a href="index.php?<?php echo http_build_query(['route' => 'admin/startups']); ?>" class="block rounded-lg px-3 py-2 hover:bg-[#519A66]">Startups</a>
                <a href="index.php?<?php echo http_build_query(['route' => 'admin/users']); ?>" class="block rounded-lg bg-[#519A66] px-3 py-2">Users</a>
                <a href="index.php?<?php echo http_build_query(['route' => 'admin/comments']); ?>" class="block rounded-lg px-3 py-2 hover:bg-[#519A66]">Comments</a>
            </nav>
        </div>
        <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/logout']); ?>">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
            <button type="submit" class="w-full rounded-lg bg-[#FFAA00] px-3 py-2 text-sm font-semibold text-white">Logout</button>
        </form>
    </aside>

    <main class="ml-64 min-h-screen bg-gray-100 px-8 py-8">
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-[#237227]">User Management</h2>
        </header>

        <div class="overflow-x-auto rounded-xl bg-white shadow">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td class="px-6 py-4 text-gray-500" colspan="5">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo e($user->id); ?></td>
                                <td class="px-6 py-4"><?php echo e($user->username ?: $user->email); ?></td>
                                <td class="px-6 py-4"><?php echo e($user->role); ?></td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($user->is_restricted)): ?>
                                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">Restricted</span>
                                    <?php else: ?>
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($user->role === 'founder'): ?>
                                        <div class="flex flex-wrap gap-2">
                                            <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/users/restrict']); ?>">
                                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                                                <input type="hidden" name="id" value="<?php echo e($user->id); ?>" />
                                                <input type="hidden" name="restricted" value="<?php echo !empty($user->is_restricted) ? 0 : 1; ?>" />
                                                <button type="submit" class="rounded bg-red-600 px-3 py-1 text-sm text-white">
                                                    <?php echo !empty($user->is_restricted) ? 'Unrestrict' : 'Restrict'; ?>
                                                </button>
                                            </form>
                                            <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/users/promote']); ?>">
                                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                                                <input type="hidden" name="id" value="<?php echo e($user->id); ?>" />
                                                <button type="submit" class="rounded bg-[#519A66] px-3 py-1 text-sm text-white">Promote to Admin</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <?php if (!empty($adminId) && $user->id === $adminId): ?>
                                            <span class="text-gray-400">You</span>
                                        <?php else: ?>
                                            <span class="text-gray-400">Admin</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
