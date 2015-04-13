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
use Webmozart\Expression\Expr;
use Webmozart\Expression\Logic\Conjunction;
use Webmozart\Expression\Logic\Disjunction;
use Webmozart\Expression\Logic\Invalid;
use Webmozart\Expression\Logic\Valid;
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

    public function testAndValidIgnored()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andValid();

        $this->assertSame($literal, $conjunction);
    }

    public function testAndXIgnoresValid()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andX(Expr::valid());

        $this->assertSame($literal, $conjunction);
    }

    public function testAndInvalidReturnsInvalid()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andInvalid();

        $this->assertInstanceOf('Webmozart\Expression\Logic\Invalid', $conjunction);
    }

    public function testAndXReturnsInvalid()
    {
        $literal = new TestLiteral('value');
        $conjunction = $literal->andX($invalid = Expr::invalid());

        $this->assertSame($invalid, $conjunction);
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testAnd($method, $args, $expected)
    {
        // tested separately
        if ('valid' === $method || 'invalid' === $method) {
            return;
        }

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

    public function testOrInvalidIgnored()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orInvalid();

        $this->assertSame($literal, $disjunction);
    }

    public function testOrXIgnoresInvalid()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orX(new Invalid());

        $this->assertSame($literal, $disjunction);
    }

    public function testOrValidReturnsValid()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orValid();

        $this->assertInstanceOf('Webmozart\Expression\Logic\Valid', $disjunction);
    }

    public function testOrXReturnsValid()
    {
        $literal = new TestLiteral('value');
        $disjunction = $literal->orX($valid = new Valid());

        $this->assertSame($valid, $disjunction);
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testOr($method, $args, $expected)
    {
        // tested separately
        if ('valid' === $method || 'invalid' === $method) {
            return;
        }

        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'or'.ucfirst($method);
        $literal = new TestLiteral();

        $result = call_user_func_array(array($literal, $method), $args);

        $this->assertEquals(new Disjunction(array($literal, $expected)), $result);
    }
}
