<?php

// Force testing environment BEFORE autoloading so Laravel's immutable Env
// repository is created with APP_ENV=testing (not the system's APP_ENV).
putenv('APP_ENV=testing');
$_ENV['APP_ENV'] = 'testing';
$_SERVER['APP_ENV'] = 'testing';

require_once __DIR__.'/../vendor/autoload.php';
