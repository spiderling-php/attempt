<?php

namespace SP\Attempt\Test;

use SP\Attempt\Attempt;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass SP\Attempt\Attempt
 */
class AttemptTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getCallback
     * @covers ::getTimeout
     * @covers ::getStep
     * @covers ::getTries
     * @covers ::getCurrent
     * @covers ::hasTriesLeft
     */
    public function testConstruct()
    {
        $callback = function () {
            return true;
        };

        $attempt = new Attempt($callback);

        $this->assertSame($callback, $attempt->getCallback());
        $this->assertSame(2000, $attempt->getTimeout());
        $this->assertSame(50, $attempt->getStep());
        $this->assertSame(40, $attempt->getTries());
        $this->assertSame(0, $attempt->getCurrent());
        $this->assertSame(true, $attempt->hasTriesLeft());
    }

    /**
     * @covers ::execute
     * @covers ::getCurrent
     */
    public function testExecute()
    {
        $increment = 0;

        $callback = function (Attempt $attempt) use (& $increment) {
            $increment += 1;
            return $attempt->getCurrent() === 3;
        };

        $attempt = new Attempt($callback);

        $attempt->execute();

        $this->assertEquals(4, $attempt->getCurrent());
        $this->assertEquals(
            4,
            $increment,
            'The function should be called the same amount of times as reported by getCurrent'
        );
    }


    /**
     * @covers ::executeOrFail
     */
    public function testExecuteOrFail()
    {
        $increment = 0;

        $callback = function (Attempt $attempt) use (& $increment) {
            $increment += 1;
            return $attempt->getCurrent() === 3;
        };

        $attempt = new Attempt($callback);

        $attempt->executeOrFail();

        $this->assertEquals(4, $attempt->getCurrent());
        $this->assertEquals(
            4,
            $increment,
            'The function should be called the same amount of times as reported by getCurrent'
        );
    }

    /**
     * @covers ::execute
     * @covers ::getCurrent
     * @covers ::setTimeout
     * @covers ::setStep
     */
    public function testExecuteTimeout()
    {
        $increment = 0;

        $callback = function (Attempt $attempt) use (& $increment) {
            $increment += 1;
            return false;
        };

        $attempt = new Attempt($callback);
        $attempt->setTimeout(200);
        $attempt->setStep(80);

        $attempt->execute();

        $this->assertEquals(3, $attempt->getCurrent());
        $this->assertEquals(
            3,
            $increment,
            'The function should be called the same amount of times as reported by getCurrent'
        );
    }

    /**
     * @covers ::executeOrFail
     */
    public function testExecuteOrFailTimeout()
    {
        $callback = function () {
            return false;
        };

        $attempt = new Attempt($callback);
        $attempt->setTimeout(80);

        $this->setExpectedException('RuntimeException', 'Timed out attempting to execute callback');

        $attempt->executeOrFail();
    }
}
