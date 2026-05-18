<section class="flex min-h-screen items-center justify-center bg-gray-200 px-4 py-12">
    <div class="w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-xl">
        <div class="bg-[#237227] px-6 py-4">
            <h1 class="text-lg font-bold text-white">CEED Admin Portal</h1>
        </div>
        <div class="px-6 py-6">
            <?php if (!empty($loginError)): ?>
                <p class="mb-4 text-sm text-red-600">Invalid username or password.</p>
            <?php endif; ?>
            <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/login']); ?>">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

                <div class="mb-4">
                    <label for="login-username" class="text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="login-username"
                        value="<?php if (!empty($_POST['username'])) echo e($_POST['username']); ?>"
                        class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-[#237227] focus:ring-[#237227]" />
                </div>

                <div class="mb-6">
                    <label for="login-password" class="text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="login-password"
                        class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-[#237227] focus:ring-[#237227]" />
                </div>

                <button type="submit" class="w-full rounded-md bg-[#FFAA00] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                    Log In to Dashboard
                </button>
            </form>
        </div>
    </div>
</section>