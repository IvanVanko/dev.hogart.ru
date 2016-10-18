<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/10/2016
 * Time: 12:39
 */

namespace Hogart\Lk\Debug;


use Hogart\Lk\Creational\Singleton;

class Timer
{
    use Singleton;
    /** @var  mixed */
    private $timer;
    /** @var array|string[]  */
    protected $timers = [];

    public function create()
    {
        if (null === $this->timer) {
            $this->timer = microtime(true);
        }
    }

    /**
     * @return mixed
     */
    public function timer()
    {
        $new_timer = microtime(true);
        $timer = $new_timer - $this->timer;
        $this->timer = $new_timer;
        return $timer;
    }

    /**
     * @return $this
     */
    public function add()
    {
        $trace = reset(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
        $this->timers[] = [
            'time' => $this->timer(),
            'line' => $trace['line'],
            'file' => $trace['file']
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getTimers()
    {
        return $this->timers;
    }
}