<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 31.05.16
 * @time 18:00
 */

namespace Tests;

class LambdaTest extends \PHPUnit_Framework_TestCase
{
    public function testUnindexedFunction()
    {
        $f = \Lambda\l('_ + _');

        $this->assertTrue(is_callable($f));

        $this->assertEquals(10, $f(4, 6));
    }

    public function testIndexedFunction()
    {
        $f = \Lambda\l('_0 + (_0 * _1)');

        $this->assertTrue(is_callable($f));

        $this->assertEquals(12, $f(4, 2));
    }

    /**
     * @expectedException \Exception
     * @throws \Exception
     */
    public function testMixingPlaceholders()
    {
        $f = \Lambda\l('_ + _0');
    }
}
