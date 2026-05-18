<h1>Student Registration</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="index.php?<?php echo http_build_query(['route' => 'account/register']); ?>">
    <input type="hidden" name="_csrf" value="<?php echo e(csrf_token()); ?>" />

    <label for="email">Institutional email:</label>
    <input type="text" name="email" id="email" value="<?php if (!empty($_POST['email'])) echo e($_POST['email']); ?>" />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" />

    <label for="confirm">Confirm password:</label>
    <input type="password" name="confirm" id="confirm" />

    <input type="submit" value="Register" />
</form>
