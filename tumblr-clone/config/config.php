<?php

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'tumbleweed');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

define('BASE_URL', 'http://localhost/tumblr-clone/public');
define('UPLOAD_DIR', __DIR__ . '/../public/assets/uploads');
define('AVATAR_DIR', __DIR__ . '/../public/assets/images');
define('DEFAULT_AVATAR', BASE_URL . '/assets/images/default-avatar.png');

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
