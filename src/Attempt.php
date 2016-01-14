<?php

namespace SP\Attempt;

use RuntimeException;

/**
 * Attempting to do something several times with a small interval, immidiately return on success
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2015, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Attempt
{
    /**
     * @var integer
     */
    private $current = 0;

    /**
     * @var integer
     */
    private $step = 50;

    /**
     * timeout in milliseconds
     *
     * @var integer
     */
    private $timeout = 2000;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param integer $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param integer $step
     */
    public function setStep($step)
    {
        $this->step = (int) $step;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @return integer
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @return boolean
     */
    public function hasTriesLeft()
    {
        return $this->current * $this->step < $this->timeout;
    }

    /**
     * @return integer
     */
    public function getTries()
    {
        return (int) ceil($this->timeout / $this->step);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $this->current = 0;

        $callback = $this->callback;

        do {
            $result = $callback($this);

            $this->current += 1;

            if (!$result) {
                usleep($this->step * 1000);
            }
        } while (!$result && $this->hasTriesLeft());

        return $result;
    }

    /**
     * Like normal execute, but throw RuntimeException on time out
     *
     * @param  string $message optional message
     * @throws RuntimeException on time out
     * @return mixed
     */
    public function executeOrFail($message = 'Timed out attempting to execute callback')
    {
        $result = $this->execute();

        if (empty($result)) {
            throw new RuntimeException($message);
        }

        return $result;
    }
}
