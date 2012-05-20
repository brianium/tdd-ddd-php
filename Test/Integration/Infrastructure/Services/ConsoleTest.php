<?php
namespace Test\Integration\Infrastructure\Services;
use Infrastructure\Services\Console;
/**
 * @author Brian Scaturro
 */
class ConsoleTest extends \PHPUnit_Framework_TestCase
{
    private $console;

    public function setUp()
    {
        $this->console = new Console();
    }

    public function testWriteLineToStdout()
    {
        $bytes = $this->console->writeLine("hello");
        $this->assertEquals(6,$bytes);
    }

    public function testConsoleInput()
    {
        $ret = $this->console->input("input",array(
           "input" => function($c) {
               return 'test';
           }
        ));
        $this->assertEquals("test",$ret);
    }

    public function testConsoleInputDefaultFunction()
    {
        $ret = $this->console->input("input",array(
            'default' => function($c) {
                return 'default';
            }
        ));
        $this->assertEquals('default',$ret);
    }

    /**
     * @expectedException   \InvalidArgumentException
     */
    public function testConsoleFunctionArrayHasAllCallable()
    {
        $ret = $this->console->input("input",array(
            'input' => 'string',
            'default' => function($c) {
                return 'default';
            }
        ));
    }
}
