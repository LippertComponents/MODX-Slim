<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 * Date: 2018-11-28
 * Time: 09:43
 */

namespace LCI\MODX\Slim;

use LCI\MODX\Console\Console;
use LCI\MODX\Slim\Helpers\Package;
use Slim\App as SlimApp;


class App
{
    const PACKAGES_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'package.php';
    // routerCacheFile

    /** @var array */
    protected $packages = [];

    /** @var array  */
    protected $settings = [
        'config' => [
            'context' => 'web',
            'additional_contexts' => []
        ],
        'settings' => [
            'displayErrorDetails' => false,
            'addContentLengthHeader' => false
        ]
    ];

    /** @var \modX */
    protected $modx;

    /** @var string  */
    protected $context = 'web';

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->loadPackages();
    }

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function runSlim()
    {
        $this->loadPackageSettings();

        /** @var \Slim\App $slim */
        $slim = new SlimApp($this->settings);

        /** @var Console $console */
        $console = new Console();
        $this->modx = $console->loadMODX();

        /** @var \Slim\Container $container */
        $container = $slim->getContainer();
        $container['modx'] = $this->modx;

        $this
            ->loadPackageDependencies($slim)
            ->loadPackageRoutes($slim);

        $slim->run();
    }

    /**
     * @param string $class ~ the fully qualified class name of a class that implements the LCI\MODX\Console\Command\PackageCommands interface
     */
    public function registerPackage($class)
    {
        if (!in_array($class, $this->packages) && is_a($class, 'LCI\MODX\Slim\Helpers\Package', true)) {
            $this->packages[] = $class;

            $this->writeCacheFile(static::PACKAGES_FILE, $this->packages);
        }
    }

    /**
     * @param string $class ~ the fully qualified class name and of the Symfony\Component\Console\Command\Command class
     */
    public function cancelRegistrationPackage($class)
    {
        if (in_array($class, $this->packages)) {
            $commands = $this->packages;
            $this->packages = [];

            foreach ($commands as $command) {
                if ($command != $class) {
                    $this->packages[] = $command;
                }
            }

            $this->writeCacheFile(static::PACKAGES_FILE, $this->packages);
        }
    }

    /**
     * @return $this
     */
    protected function loadPackages()
    {
        if (file_exists(static::PACKAGES_FILE)) {
            $this->packages = include static::PACKAGES_FILE;
        }

        return $this;
    }

    /**
     * @param SlimApp $app
     * @return $this
     */
    protected function loadPackageDependencies(\Slim\App $app)
    {
        $this->settings = [];

        foreach ($this->packages as $package_class) {
            /** @var \LCI\MODX\Slim\Helpers\Package $class */
            try {
                $class = new $package_class();

                if ($class instanceof Package) {
                    $class->loadDependencies($app);
                }
            } catch (\Exception $exception) {
                // @TODO log error
            }
        }

        return $this;
    }

    /**
     * @param SlimApp $app
     * @return $this
     */
    protected function loadPackageRoutes(\Slim\App $app)
    {
        $this->settings = [];

        foreach ($this->packages as $package_class) {
            /** @var \LCI\MODX\Slim\Helpers\Package $class */
            try {
                $class = new $package_class();

                if ($class instanceof Package) {
                    $class->loadRoutes($app);
                }
            } catch (\Exception $exception) {
                // @TODO log error
            }
        }

        return $this;
    }

    /**
     *
     */
    protected function loadPackageSettings()
    {
        $this->settings = [];

        foreach ($this->packages as $package_class) {
            /** @var \LCI\MODX\Slim\Helpers\Package $class */
            try {
                $class = new $package_class();

                if ($class instanceof Package) {
                    $this->settings = $class->loadSettings($this->settings);
                }
            } catch (\Exception $exception) {
                // @TODO log error
            }
        }
    }

    /**
     * @param string $file
     * @param array $data
     */
    protected function writeCacheFile($file, $data)
    {
        $content = '<?php ' . PHP_EOL .
            'return ' . var_export($data, true) . ';';

        file_put_contents($file, $content);
    }

}