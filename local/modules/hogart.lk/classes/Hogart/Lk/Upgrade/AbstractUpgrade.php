<?php

namespace Hogart\Lk\Upgrade;

abstract class AbstractUpgrade
{
    protected $debug = false;

    public function setDebug($debug = false){
        $this->debug = $debug;
    }

    abstract public function doUpgrade();
}
