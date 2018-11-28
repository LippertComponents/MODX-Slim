<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 * Date: 2018-11-28
 * Time: 09:57
 */

namespace LCI\MODX\Slim\Helpers;

/**
 * Interface Package
 * Will allow a package to define: settings, dependencies and routes
 * @package LCI\MODX\Slim\Helpers
 */
interface Package
{

    /**
     * @param array $existing_settings
     * @return array
     */
    public function loadSettings(array $existing_settings):array;

    /**
     * @param \Slim\App $app
     */
    public function loadDependencies(\Slim\App $app):void;

    /**
     * @param \Slim\App $app
     */
    public function loadRoutes(\Slim\App $app):void;
}