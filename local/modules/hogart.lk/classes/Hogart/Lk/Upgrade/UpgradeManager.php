<?php

namespace Hogart\Lk\Upgrade;

class UpgradeManager
{
    protected $debug = false;
    protected $files = [];

    public function __construct($debug = false)
    {
        $this->debug = $debug;

        $version = $this->getUpgradeVersion();

        $this->files = $this->getFiles();
        $offset = array_search($version, $this->files);
        if ($offset !== false) {
            $this->files = array_slice($this->files, $offset + 1);
        }
    }

    /**
     * @return bool
     */
    public function isUpgradeNeeded()
    {
        return (bool) count($this->files);
    }

    public function upgradeIfNeed()
    {
        foreach ($this->files as $upgradeName) {
            $this->doUpgrade($upgradeName);
        }
    }

    public function upgradeReload()
    {
        Module::setDbOption('upgrade_version', 'unknown');
        $this->upgradeIfNeed();
    }


    public function getUpgradeVersion()
    {
        return Module::getDbOption('upgrade_version', 'unknown');
    }


    protected function doUpgrade($name)
    {
        $upgradeFile = Module::getUpgradeDir() . '/' . $name . '.php';

        if (!is_file($upgradeFile)) {
            return false;
        }

        /** @noinspection PhpIncludeInspection */
        require_once($upgradeFile);

        $class = 'Hogart\Lk\Upgrade\\' . $name;

        if (!class_exists($class)) {
            return false;
        }

        /** @var AbstractUpgrade $obj */
        $obj = new $class();
        $obj->setDebug($this->debug);
        $obj->doUpgrade();

        Module::setDbOption('upgrade_version', $name);

        return true;
    }

    protected function getFiles()
    {
        $directory = new \DirectoryIterator(Module::getUpgradeDir());

        $files = array();
        /* @var $item \SplFileInfo */
        foreach ($directory as $item) {
            $fileName = pathinfo($item->getPathname(), PATHINFO_FILENAME);
            if ($this->checkUpgradeName($fileName)) {
                $files[] = $fileName;
            }
        }

        sort($files);

        return $files;
    }

    protected function checkUpgradeName($fileName)
    {
        return preg_match('/^Upgrade\d+$/i', $fileName);
    }

}
