<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./styles/simple.css" />
    <link rel="stylesheet" type="text/css" href="./styles/custom.css" />
    <title>CMS Project</title>
</head>
<body>
    <header>
        <h1>
            <a href="index.php">CMS Project</a>
        </h1>
        <p>A custom-made CMS system</p>
        <nav>
            <a href="index.php?<?php echo http_build_query(['route' => 'startups']); ?>">Showcase</a>
            <?php foreach($categories AS $category): ?>
                <a href="index.php?<?php echo http_build_query(['route' => 'startups', 'category' => $category->slug]); ?>">
                    <?php echo e($category->name); ?>
                </a>
            <?php endforeach; ?>
            <a href="index.php?<?php echo http_build_query(['route' => 'startups/create']); ?>">Submit Startup</a>
        </nav>
    </header>
    <main>
        <?php echo $contents; ?>
    </main>
    <footer>
        <p></p>
    </footer>
</body>
</html>