<?php

namespace Serenity\Common;

/**
 * Provides functionality for loading classes.
 *
 * @category Serenity
 * @package  Common
 */
class ClassLoader
{
    /**
     * Root directories where to start finding classes.
     *
     * @var array
     */
    private $classDirs = array();

    /**
     * Add root directory where to start finding classes.
     *
     * @param string $classDir Root directory where to start finding classes.
     *
     * @return ClassLoader Self instance.
     *
     * @throws \InvalidArgumentException If the given directory does not exist.
     */
    public function addClassDir($classDir)
    {
        if (false === ($this->classDirs[] = \realpath((string) $classDir))) {
            $message = "The given directory '$classDir' does not exist.";
            throw new \InvalidArgumentException($message);
        }

        return $this;
    }

    /**
     * Add a list of root directories where to start finding classes.
     *
     * @param array $classDirs A list of root directories where to start
     *                         finding classes.
     *
     * @return ClassLoader Self instance.
     */
    public function addClassDirs(array $classDirs)
    {
        foreach ($classDirs as $classDir) {
            $this->addClassDir($classDir);
        }

        return $this;
    }

    /**
     * Try to load specified class.
     *
     * @param string $className Name of the class that should be loaded.
     *
     * @return bool True if the class was found and loaded, false otherwise.
     */
    public function loadClass($className)
    {
        $ds = \DIRECTORY_SEPARATOR;
        foreach ($this->classDirs as $classDir) {
            $classPath = $classDir . $ds
                . \str_replace(array('\\', '_'), $ds, $className) . '.php';

            if (\file_exists($classPath)) {
                require $classPath;
                return true;
            }
        }

        return false;
    }

    /**
     * Enable or disable autoloading of the classes.
     *
     * @param bool $enable Enable or disable autoloading.
     */
    public function enableAutoloading($enable = true)
    {
        $loader = array($this, 'loadClass');

        if ((bool) $enable) {
            \spl_autoload_register($loader);
        } else {
            \spl_autoload_unregister($loader);
        }
    }
}
