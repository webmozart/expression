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
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Logic\AlwaysFalse;
use Webmozart\Expression\Logic\AlwaysTrue;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Tests\Logic\Fixtures\TestLiteral;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LiteralTest extends PHPUnit_Framework_TestCase
{
    public function testAndX()
    {
        $literal = new TestLiteral();
        $expr = new Same('10');

        $this->assertEquals(new AndX(array($literal, $expr)), $literal->andX($expr));
    }

    public function testAndXIgnoresDuplicates()
    {
        $literal = new TestLiteral('value');

        $this->assertEquals($literal, $literal->andX(new TestLiteral('value')));
    }

    public function testAndTrueIgnored()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andTrue();

        $this->assertSame($literal, $conjunction);
    }

    public function testAndXIgnoresTrue()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andX(Expr::true());

        $this->assertSame($literal, $conjunction);
    }

    public function testAndFalseReturnsFalse()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andFalse();

        $this->assertInstanceOf('Webmozart\Expression\Logic\AlwaysFalse', $conjunction);
    }

    public function testAndXReturnsFalse()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andX($false = Expr::false());

        $this->assertSame($false, $conjunction);
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testAnd($method, $args, $expected)
    {
        // tested separately
        if ('true' === $method || 'false' === $method) {
            return;
        }

        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'and'.ucfirst($method);
        $literal = new TestLiteral();

        $result = call_user_func_array(array($literal, $method), $args);

        $this->assertEquals(new AndX(array($literal, $expected)), $result);
    }

    public function testOrX()
    {
        $literal = new TestLiteral();
        $expr = new Same('10');

        $this->assertEquals(new OrX(array($literal, $expr)), $literal->orX($expr));
    }

    public function testOrXIgnoresDuplicates()
    {
        $literal = new TestLiteral('value');

        $this->assertEquals($literal, $literal->orX(new TestLiteral('value')));
    }

    public function testOrFalseIgnored()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orFalse();

        $this->assertSame($literal, $disjunction);
    }

    public function testOrXIgnoresFalse()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orX(new AlwaysFalse());

        $this->assertSame($literal, $disjunction);
    }

    public function testOrTrueReturnsTrue()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orTrue();

        $this->assertInstanceOf('Webmozart\Expression\Logic\AlwaysTrue', $disjunction);
    }

    public function testOrXReturnsTrue()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orX($true = new AlwaysTrue());

        $this->assertSame($true, $disjunction);
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testOr($method, $args, $expected)
    {
        // tested separately
        if ('true' === $method || 'false' === $method) {
            return;
        }

        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'or'.ucfirst($method);
        $literal = new TestLiteral();

        $result = call_user_func_array(array($literal, $method), $args);

        $this->assertEquals(new OrX(array($literal, $expected)), $result);
    }
}
