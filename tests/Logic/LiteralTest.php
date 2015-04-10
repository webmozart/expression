<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Logic;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Logic\Conjunction;
use Webmozart\Expression\Logic\Disjunction;
use Webmozart\Expression\Tests\Logic\Fixtures\TestLiteral;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LiteralTest extends PHPUnit_Framework_TestCase
{
    public function testAndX()
    {
        $literal = new TestLiteral();
        $expr = new Same('10');

        $this->assertEquals(new Conjunction(array($literal, $expr)), $literal->andX($expr));
    }

    public function testAndXIgnoresDuplicates()
    {
        $literal = new TestLiteral('value');

        $this->assertEquals($literal, $literal->andX(new TestLiteral('value')));
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testAnd($method, $args, $expected)
    {
        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'and'.ucfirst($method);
        $literal = new TestLiteral();

        $result = call_user_func_array(array($literal, $method), $args);

        $this->assertEquals(new Conjunction(array($literal, $expected)), $result);
    }

    public function testOrX()
    {
        $literal = new TestLiteral();
        $expr = new Same('10');

        $this->assertEquals(new Disjunction(array($literal, $expr)), $literal->orX($expr));
    }

    public function testOrXIgnoresDuplicates()
    {
        $literal = new TestLiteral('value');

        $this->assertEquals($literal, $literal->orX(new TestLiteral('value')));
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testOr($method, $args, $expected)
    {
        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'or'.ucfirst($method);
        $literal = new TestLiteral();

        $result = call_user_func_array(array($literal, $method), $args);

        $this->assertEquals(new Disjunction(array($literal, $expected)), $result);
    }
}
