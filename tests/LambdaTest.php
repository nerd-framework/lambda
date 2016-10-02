<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 31.05.16
 * @time 18:00
 */
namespace tests;

use function Nerd\Lambda\l;

class LambdaTest extends \PHPUnit_Framework_TestCase
{
    public function testUnindexedFunction()
    {
        $f = l('$ + $');

        $this->assertTrue(is_callable($f));

        $this->assertEquals(10, $f(4, 6));
    }

    public function testIndexedFunction()
    {
        $f = l('$0 + ($0 * $1)');

        $this->assertTrue(is_callable($f));

        $this->assertEquals(12, $f(4, 2));
    }

    public function testNegFunction()
    {
        $this->assertEquals(-12, call_user_func(l('-($)'), 12));
    }

    public function testAsFilter()
    {
        $arr = range(1, 10);
        $filtered = array_values(array_filter($arr, l('!($ & 1)')));

        $this->assertEquals([2, 4, 6, 8, 10], $filtered);
    }

    /**
     * @expectedException \Exception
     *
     * @throws \Exception
     */
    public function testMixingPlaceholders()
    {
        l('$ + $0');
    }
}
