<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Selector;

use PHPUnit_Framework_TestCase;
use stdClass;
use Webmozart\Expression\Constraint\EndsWith;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Selector\Method;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class MethodTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Method('getFoo', array(), new GreaterThan(10));

        $this->assertTrue($expr->evaluate(new MethodTest_TestClass(11)));
        $this->assertFalse($expr->evaluate(new MethodTest_TestClass(9)));
        $this->assertFalse($expr->evaluate('foobar'));
        $this->assertFalse($expr->evaluate(new stdClass()));
    }

    public function testEvaluateWithArguments()
    {
        $expr = new Method('getBar', array(2), new GreaterThan(10));

        $this->assertTrue($expr->evaluate(new MethodTest_TestClass(9)));
        $this->assertFalse($expr->evaluate(new MethodTest_TestClass(7)));
    }

    public function testToString()
    {
        $expr1 = new Method('getName', array(42, true), new GreaterThan(10));
        $expr2 = new Method('getName', array('foo'), new EndsWith('.css'));
        $expr3 = new Method('getName', array(new stdClass()), new AndX(array(
            new GreaterThan(10),
            new EndsWith('.css'),
        )));

        $this->assertSame('getName(42, true)>10', $expr1->toString());
        $this->assertSame('getName("foo").endsWith(".css")', $expr2->toString());
        $this->assertSame('getName(object){>10 && endsWith(".css")}', $expr3->toString());
    }
}

class MethodTest_TestClass
{
    private $foo;

    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function getBar($x)
    {
        return $this->foo + $x;
    }
}
