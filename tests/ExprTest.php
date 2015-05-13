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

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\Contains;
use Webmozart\Expression\Comparison\EndsWith;
use Webmozart\Expression\Comparison\Equals;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\GreaterThanEqual;
use Webmozart\Expression\Comparison\IsEmpty;
use Webmozart\Expression\Comparison\KeyExists;
use Webmozart\Expression\Comparison\KeyNotExists;
use Webmozart\Expression\Comparison\LessThan;
use Webmozart\Expression\Comparison\LessThanEqual;
use Webmozart\Expression\Comparison\Matches;
use Webmozart\Expression\Comparison\NotEmpty;
use Webmozart\Expression\Comparison\NotEquals;
use Webmozart\Expression\Comparison\NotSame;
use Webmozart\Expression\Comparison\In;
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Comparison\StartsWith;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Logic\AlwaysFalse;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\AlwaysTrue;
use Webmozart\Expression\Selector\All;
use Webmozart\Expression\Selector\AtLeast;
use Webmozart\Expression\Selector\AtMost;
use Webmozart\Expression\Selector\Count;
use Webmozart\Expression\Selector\Exactly;
use Webmozart\Expression\Selector\Key;

/**
 * @since  1.0
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
                new Same(null)
            ),
            array(
                'notNull',
                array(),
                new NotSame(null)
            ),
            array(
                'isEmpty',
                array(),
                new IsEmpty()
            ),
            array(
                'notEmpty',
                array(),
                new NotEmpty()
            ),
            array(
                'equals',
                array(10),
                new Equals(10)
            ),
            array(
                'notEquals',
                array(10),
                new NotEquals(10)
            ),
            array(
                'same',
                array(10),
                new Same(10)
            ),
            array(
                'notSame',
                array(10),
                new NotSame(10)
            ),
            array(
                'greaterThan',
                array(10),
                new GreaterThan(10)
            ),
            array(
                'greaterThanEqual',
                array(10),
                new GreaterThanEqual(10)
            ),
            array(
                'lessThan',
                array(10),
                new LessThan(10)
            ),
            array(
                'lessThanEqual',
                array(10),
                new LessThanEqual(10)
            ),
            array(
                'matches',
                array('~^\d{4}$~'),
                new Matches('~^\d{4}$~')
            ),
            array(
                'startsWith',
                array('Thomas'),
                new StartsWith('Thomas')
            ),
            array(
                'endsWith',
                array('.css'),
                new EndsWith('.css')
            ),
            array(
                'contains',
                array('css'),
                new Contains('css')
            ),
            array(
                'in',
                array(array('1', '2', '3')),
                new In(array('1', '2', '3'))
            ),
        );
    }

    public static function getMethodTests()
    {
        $expr = new Same('10');

        $tests = array_merge(array(
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

        // Add tests for the $key arguments
        foreach (self::getComparisons() as $comparisonTest) {
            $tests[] = array(
                $comparisonTest[0],
                array_merge($comparisonTest[1], array('field')),
                new Key('field', $comparisonTest[2]),
            );
        }

        return $tests;
    }

    /**
     * @dataProvider getMethodTests
     */
    public function testCreate($method, $args, $expected)
    {
        $this->assertEquals($expected, call_user_func_array(array('Webmozart\Expression\Expr', $method), $args));
    }
}
