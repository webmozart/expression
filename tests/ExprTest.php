<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests;

use ArrayObject;
use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Constraint\Contains;
use Webmozart\Expression\Constraint\EndsWith;
use Webmozart\Expression\Constraint\Equals;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Constraint\GreaterThanEqual;
use Webmozart\Expression\Constraint\In;
use Webmozart\Expression\Constraint\IsEmpty;
use Webmozart\Expression\Constraint\IsInstanceOf;
use Webmozart\Expression\Constraint\KeyExists;
use Webmozart\Expression\Constraint\KeyNotExists;
use Webmozart\Expression\Constraint\LessThan;
use Webmozart\Expression\Constraint\LessThanEqual;
use Webmozart\Expression\Constraint\Matches;
use Webmozart\Expression\Constraint\NotEquals;
use Webmozart\Expression\Constraint\NotSame;
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Constraint\StartsWith;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Logic\AlwaysFalse;
use Webmozart\Expression\Logic\AlwaysTrue;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Selector\All;
use Webmozart\Expression\Selector\AtLeast;
use Webmozart\Expression\Selector\AtMost;
use Webmozart\Expression\Selector\Count;
use Webmozart\Expression\Selector\Exactly;
use Webmozart\Expression\Selector\Key;
use Webmozart\Expression\Selector\Method;
use Webmozart\Expression\Selector\Property;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ExprTest extends PHPUnit_Framework_TestCase
{
    public static function getComparisons()
    {
        return array(
            array(
                'keyExists',
                array('key'),
                new KeyExists('key'),
            ),
            array(
                'keyNotExists',
                array('key'),
                new KeyNotExists('key'),
            ),
            array(
                'null',
                array(),
                new Same(null),
            ),
            array(
                'notNull',
                array(),
                new NotSame(null),
            ),
            array(
                'isEmpty',
                array(),
                new IsEmpty(),
            ),
            array(
                'isInstanceOf',
                array('DateTime'),
                new IsInstanceOf('DateTime'),
            ),
            array(
                'notEmpty',
                array(),
                new Not(new IsEmpty()),
            ),
            array(
                'equals',
                array(10),
                new Equals(10),
            ),
            array(
                'notEquals',
                array(10),
                new NotEquals(10),
            ),
            array(
                'same',
                array(10),
                new Same(10),
            ),
            array(
                'notSame',
                array(10),
                new NotSame(10),
            ),
            array(
                'greaterThan',
                array(10),
                new GreaterThan(10),
            ),
            array(
                'greaterThanEqual',
                array(10),
                new GreaterThanEqual(10),
            ),
            array(
                'lessThan',
                array(10),
                new LessThan(10),
            ),
            array(
                'lessThanEqual',
                array(10),
                new LessThanEqual(10),
            ),
            array(
                'matches',
                array('~^\d{4}$~'),
                new Matches('~^\d{4}$~'),
            ),
            array(
                'startsWith',
                array('Thomas'),
                new StartsWith('Thomas'),
            ),
            array(
                'endsWith',
                array('.css'),
                new EndsWith('.css'),
            ),
            array(
                'contains',
                array('css'),
                new Contains('css'),
            ),
            array(
                'in',
                array(array('1', '2', '3')),
                new In(array('1', '2', '3')),
            ),
        );
    }

    public static function getMethodTests()
    {
        $expr = new Same('10');

        return array_merge(array(
            array(
                'not',
                array($expr),
                new Not($expr),
            ),
            array(
                'key',
                array('key', $expr),
                new Key('key', $expr),
            ),
            array(
                'method',
                array('getFoo', $expr),
                new Method('getFoo', array(), $expr),
            ),
            array(
                'method',
                array('getFoo', 42, 'bar', $expr),
                new Method('getFoo', array(42, 'bar'), $expr),
            ),
            array(
                'property',
                array('prop', $expr),
                new Property('prop', $expr),
            ),
            array(
                'atLeast',
                array(2, $expr),
                new AtLeast(2, $expr),
            ),
            array(
                'atMost',
                array(2, $expr),
                new AtMost(2, $expr),
            ),
            array(
                'exactly',
                array(2, $expr),
                new Exactly(2, $expr),
            ),
            array(
                'all',
                array($expr),
                new All($expr),
            ),
            array(
                'count',
                array($expr),
                new Count($expr),
            ),
            array(
                'true',
                array(),
                new AlwaysTrue(),
            ),
            array(
                'false',
                array(),
                new AlwaysFalse(),
            ),
        ), self::getComparisons());
    }

    /**
     * @dataProvider getMethodTests
     */
    public function testCreate($method, $args, $expected)
    {
        $this->assertEquals($expected, call_user_func_array(array('Webmozart\Expression\Expr', $method), $args));
    }

    public function testExpr()
    {
        $expr = new Same(true);

        $this->assertSame($expr, Expr::expr($expr));
    }

    public function testAndX()
    {
        $andX = new AndX(array(new GreaterThan(5), new LessThan(10)));

        $this->assertEquals($andX, Expr::andX(array(Expr::greaterThan(5), Expr::lessThan(10))));
    }

    public function testOrX()
    {
        $andX = new OrX(array(new LessThan(5), new GreaterThan(10)));

        $this->assertEquals($andX, Expr::orX(array(Expr::lessThan(5), Expr::greaterThan(10))));
    }

    public function testFilterArray()
    {
        $input = range(1, 10);
        $output = array_filter($input, function ($i) { return $i > 4; });

        $this->assertSame($output, Expr::filter($input, Expr::greaterThan(4)));
    }

    public function testFilterCollection()
    {
        $input = new ArrayObject(range(1, 10));
        $output = new ArrayObject(array_filter(range(1, 10), function ($i) { return $i > 4; }));

        $this->assertEquals($output, Expr::filter($input, Expr::greaterThan(4)));
    }
}
