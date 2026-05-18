<h1>Submit a Startup</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" action="index.php?<?php echo http_build_query(['route' => 'startups/create']); ?>">
    <input type="hidden" name="_csrf" value="<?php echo e(csrf_token()); ?>" />

    <label for="name">Startup name:</label>
    <input type="text" name="name" id="name" value="<?php if (!empty($_POST['name'])) echo e($_POST['name']); ?>" />

    <label for="category_id">Category:</label>
    <select name="category_id" id="category_id">
        <option value="">Select a category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo e($category->id); ?>" <?php if (!empty($_POST['category_id']) && (int) $_POST['category_id'] === (int) $category->id) echo 'selected'; ?>>
                <?php echo e($category->name); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="short_pitch">Short pitch:</label>
    <input type="text" name="short_pitch" id="short_pitch" value="<?php if (!empty($_POST['short_pitch'])) echo e($_POST['short_pitch']); ?>" />

    <label for="description">Full description:</label>
    <textarea name="description" id="description"><?php if (!empty($_POST['description'])) echo e($_POST['description']); ?></textarea>

    <label for="team">Team members:</label>
    <input type="text" name="team" id="team" value="<?php if (!empty($_POST['team'])) echo e($_POST['team']); ?>" />

    <label for="stage">Stage:</label>
    <input type="text" name="stage" id="stage" value="<?php if (!empty($_POST['stage'])) echo e($_POST['stage']); ?>" />

    <label for="drive_link">Google Drive link (optional):</label>
    <input type="text" name="drive_link" id="drive_link" value="<?php if (!empty($_POST['drive_link'])) echo e($_POST['drive_link']); ?>" />

    <label for="media">Images or short videos:</label>
    <input type="file" name="media[]" id="media" multiple />

    <input type="submit" value="Submit Startup" />
</form>
