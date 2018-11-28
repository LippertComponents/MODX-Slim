<?php
use LCI\MODX\Slim\App;

$autoload_possible_paths = [
    // if cloned from git:
    dirname(dirname(dirname(__DIR__))).'/vendor/autoload.php',
    // if installed via composer:
    dirname(dirname(dirname(__DIR__))).'/core/vendor/autoload.php',
];
foreach ($autoload_possible_paths as $autoload_path) {
    if (file_exists($autoload_path)) {
        require_once $autoload_path;
        break;
    }
}

$modxSlimApp = new App();

$modxSlimApp->runSlim();