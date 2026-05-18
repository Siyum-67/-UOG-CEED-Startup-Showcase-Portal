<?php

require __DIR__ . '/inc/all.inc.php';

$container = new \App\Support\Container();
$container->bind('pdo', function() {
    return require __DIR__ . '/inc/db-connect.inc.php';
});
$container->bind('authService', function() use($container) {
    $pdo = $container->get('pdo');
    return new \App\Admin\Support\AuthService($pdo);
});
$container->bind('categoriesRepository', function() use($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\CategoriesRepository($pdo);
});
$container->bind('startupsRepository', function() use($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\StartupsRepository($pdo);
});
$container->bind('commentsRepository', function() use($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\CommentsRepository($pdo);
});
$container->bind('mediaRepository', function() use($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\MediaRepository($pdo);
});
$container->bind('usersRepository', function() use($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\UsersRepository($pdo);
});
$container->bind('uploadService', function() {
    return new \App\Support\UploadService(__DIR__ . '/uploads');
});
$container->bind('startupsController', function() use($container) {
    return new \App\Frontend\Controller\StartupsController(
        $container->get('startupsRepository'),
        $container->get('categoriesRepository'),
        $container->get('commentsRepository'),
        $container->get('mediaRepository'),
        $container->get('uploadService'),
        $container->get('authService')
    );
});
$container->bind('publicController', function() use($container) {
    return new \App\Frontend\Controller\PublicController(
        $container->get('startupsRepository'),
        $container->get('categoriesRepository'),
        $container->get('mediaRepository'),
        $container->get('usersRepository')
    );
});
$container->bind('commentsController', function() use($container) {
    return new \App\Frontend\Controller\CommentsController(
        $container->get('commentsRepository'),
        $container->get('startupsRepository')
    );
});
$container->bind('authController', function() use($container) {
    return new \App\Frontend\Controller\AuthController(
        $container->get('authService')
    );
});
$container->bind('studentController', function() use($container) {
    return new \App\Frontend\Controller\StudentController(
        $container->get('authService'),
        $container->get('startupsRepository'),
        $container->get('categoriesRepository'),
        $container->get('usersRepository'),
        $container->get('mediaRepository'),
        $container->get('uploadService')
    );
});
$container->bind('notFoundController', function() use($container) {
    return new \App\Frontend\Controller\NotFoundController(
        $container->get('categoriesRepository'),
        $container->get('authService')
    );
});
$container->bind('startupsAdminController', function() use($container) {
    return new \App\Admin\Controller\StartupsAdminController(
        $container->get('authService'),
        $container->get('startupsRepository'),
        $container->get('mediaRepository')
    );
});
$container->bind('usersAdminController', function() use($container) {
    return new \App\Admin\Controller\UsersAdminController(
        $container->get('authService'),
        $container->get('usersRepository')
    );
});
$container->bind('commentsAdminController', function() use($container) {
    return new \App\Admin\Controller\CommentsAdminController(
        $container->get('authService'),
        $container->get('commentsRepository')
    );
});
$container->bind('loginController', function() use($container) {
    $authService = $container->get('authService');
    return new \App\Admin\Controller\LoginController(
        $authService
    );
});
$container->bind('csrfHelper', function() {
    return new \App\Support\CsrfHelper();
});

$csrfHelper = $container->get('csrfHelper');
$csrfHelper->handle();

if (!empty($_SESSION['founderUserId'])) {
    $user = $container->get('usersRepository')->findById((int) $_SESSION['founderUserId']);
    if (!empty($user)) {
        $_SESSION['founderName'] = $user->full_name ?? '';
        $_SESSION['founderAvatar'] = $user->avatar_path ?? '';
        $_SESSION['founderEmail'] = $user->email ?? '';
    }
}

// var_dump($csrfHelper->generateToken());
function csrf_token() {
    global $container;
    $csrfHelper = $container->get('csrfHelper');
    return $csrfHelper->generateToken();
}

$route = @(string) ($_GET['route'] ?? 'showcase');

if ($route === 'showcase') {
    $publicController = $container->get('publicController');
    $publicController->showcase();
}
else if ($route === 'startups') {
    $startupsController = $container->get('startupsController');
    $startupsController->index();
}
else if ($route === 'startups/show') {
    $startupsController = $container->get('startupsController');
    $startupsController->show();
}
else if ($route === 'comments/store') {
    $commentsController = $container->get('commentsController');
    $commentsController->store();
}
else if ($route === 'founder/show') {
    $publicController = $container->get('publicController');
    $publicController->founderShow();
}
else if ($route === 'startups/create') {
    $startupsController = $container->get('startupsController');
    $startupsController->create();
}
else if ($route === 'startups/edit') {
    $startupsController = $container->get('startupsController');
    $startupsController->edit();
}
else if ($route === 'student/register') {
    $authController = $container->get('authController');
    $authController->showRegister();
}
else if ($route === 'student/login') {
    $authController = $container->get('authController');
    $authController->showLogin();
}
else if ($route === 'student/submit') {
    $studentController = $container->get('studentController');
    $studentController->create();
}
else if ($route === 'student/dashboard') {
    $studentController = $container->get('studentController');
    $studentController->dashboard();
}
else if ($route === 'student/profile') {
    $studentController = $container->get('studentController');
    $studentController->profile();
}
else if ($route === 'student/profile/update') {
    $studentController = $container->get('studentController');
    $studentController->updateProfile();
}
else if ($route === 'student/logout') {
    $studentController = $container->get('studentController');
    $studentController->logout();
}
else if ($route === 'student/startups/delete') {
    $studentController = $container->get('studentController');
    $studentController->deleteStartup();
}
else if (
    $route === 'account/register' ||
    $route === 'account/login' ||
    $route === 'account/logout' ||
    $route === 'account/startups'
) {
    header('Location: index.php?' . http_build_query(['route' => 'showcase']));
    return;
}
else if ($route === 'admin/login') {
    $loginController = $container->get('loginController');
    $loginController->login();
}
else if ($route === 'admin/logout') {
    $loginController = $container->get('loginController');
    $loginController->logout();
}
else if ($route === 'admin/startups') {
    $authService = $container->get('authService');
    $authService->ensureAdminLoggedIn();

    $startupsAdminController = $container->get('startupsAdminController');
    $startupsAdminController->index();
}
else if ($route === 'admin/startups/delete') {
    $authService = $container->get('authService');
    $authService->ensureAdminLoggedIn();

    $startupsAdminController = $container->get('startupsAdminController');
    $startupsAdminController->delete();
}
else if ($route === 'admin/users') {
    $authService = $container->get('authService');
    $authService->ensureAdminLoggedIn();

    $usersAdminController = $container->get('usersAdminController');
    $usersAdminController->index();
}
else if ($route === 'admin/users/restrict') {
    $authService = $container->get('authService');
    $authService->ensureAdminLoggedIn();

    $usersAdminController = $container->get('usersAdminController');
    $usersAdminController->restrict();
}
else if ($route === 'admin/users/promote') {
    $authService = $container->get('authService');
    $authService->ensureAdminLoggedIn();

    $usersAdminController = $container->get('usersAdminController');
    $usersAdminController->promote();
}
else if ($route === 'admin/comments') {
    $authService = $container->get('authService');
    $authService->ensureAdminLoggedIn();

    $commentsAdminController = $container->get('commentsAdminController');
    $commentsAdminController->index();
}
else if ($route === 'admin/comments/delete') {
    $authService = $container->get('authService');
    $authService->ensureAdminLoggedIn();

    $commentsAdminController = $container->get('commentsAdminController');
    $commentsAdminController->delete();
}
else {
    $notFoundController = $container->get('notFoundController');
    $notFoundController->error404();
}