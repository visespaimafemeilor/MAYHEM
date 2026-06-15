<?php

return [
    '/' => ['DashboardController', 'index'],
    '/login' => ['AuthController', 'login'],
    '/register' => ['AuthController', 'register'],
    '/logout' => ['AuthController', 'logout'],
    '/dashboard' => ['DashboardController', 'index'],
    '/create' => ['PostController', 'create'],
    '/edit' => ['PostController', 'edit'],
    '/delete' => ['PostController', 'delete'],
    '/profile' => ['ProfileController', 'index'],
    '/settings' => ['SettingsController', 'index'],
    '/settings/avatar' => ['SettingsController', 'updateAvatar'],
    '/settings/password' => ['SettingsController', 'updatePassword'],
    '/like' => ['PostController', 'like'],
    '/follow' => ['ProfileController', 'follow'],
];
