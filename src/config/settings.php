<?php
$display_errors = true;
if ($display_errors) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

/** @var array $config */
$config = [];
$config['displayErrorDetails'] = $display_errors;
$config['addContentLengthHeader'] = false;

/** @var array $settings */
$settings = [
    'config' => [
        'context' => 'web',
        'additional_contexts' => []
    ],
    'settings' => $config
];
