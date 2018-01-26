<?php

/** @var \Slim\Container $container */
$container = $app->getContainer();

// get MODX:
$modx_config_file = dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'config.core.php';
if (file_exists($modx_config_file)) {
    require_once $modx_config_file;
    require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
    /** @var \modX */
    $container['modx'] = new \modX();
    $container['modx']->initialize('web');
}
