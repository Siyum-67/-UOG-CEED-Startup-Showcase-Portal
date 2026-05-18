<h1>My Startups</h1>

<p><a href="index.php?<?php echo http_build_query(['route' => 'startups/create']); ?>">Create a new startup</a></p>

<?php if (empty($startups)): ?>
    <p>You have not submitted any startups yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($startups as $startup): ?>
            <li>
                <strong><?php echo e($startup->name); ?></strong>
                <a href="index.php?<?php echo http_build_query(['route' => 'startups/show', 'slug' => $startup->slug]); ?>">View</a>
                <a href="index.php?<?php echo http_build_query(['route' => 'startups/edit', 'id' => $startup->id]); ?>">Edit</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
