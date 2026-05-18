<h1>Startup Showcase</h1>

<?php if (!empty($category)): ?>
    <p>Category: <strong><?php echo e($category->name); ?></strong></p>
<?php endif; ?>

<?php if (empty($startups)): ?>
    <p>No startups have been posted yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($startups as $startup): ?>
            <li>
                <a href="index.php?<?php echo http_build_query(['route' => 'startups/show', 'slug' => $startup->slug]); ?>">
                    <?php echo e($startup->name); ?>
                </a>
                <p><?php echo e($startup->short_pitch); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
