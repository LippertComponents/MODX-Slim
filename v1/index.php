<?php
use Slim\App;

require dirname(__DIR__).'/vendor/autoload.php';

/** @var array $settings ~ overridden in settings.php */
$settings = [];
require_once dirname(__DIR__) . '/src/config/settings.php';

/** @var App $app */
$app = new App($settings);

// Set up dependencies, MODX and your project
require_once dirname(__DIR__) . '/src/config/dependencies.php';

// Register routes
require_once dirname(__DIR__) . '/src/config/routes.php';

$app->run();
