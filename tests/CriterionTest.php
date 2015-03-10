<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Comparison\EndsWith;
use Webmozart\Criteria\Comparison\Equals;
use Webmozart\Criteria\Comparison\False;
use Webmozart\Criteria\Comparison\GreaterThan;
use Webmozart\Criteria\Comparison\GreaterThanEqual;
use Webmozart\Criteria\Comparison\IsEmpty;
use Webmozart\Criteria\Comparison\LessThan;
use Webmozart\Criteria\Comparison\LessThanEqual;
use Webmozart\Criteria\Comparison\Matches;
use Webmozart\Criteria\Comparison\NotEmpty;
use Webmozart\Criteria\Comparison\NotEquals;
use Webmozart\Criteria\Comparison\NotNull;
use Webmozart\Criteria\Comparison\NotSame;
use Webmozart\Criteria\Comparison\Null;
use Webmozart\Criteria\Comparison\OneOf;
use Webmozart\Criteria\Comparison\Same;
use Webmozart\Criteria\Comparison\StartsWith;
use Webmozart\Criteria\Comparison\True;
use Webmozart\Criteria\Criterion;
use Webmozart\Criteria\Key\Key;
use Webmozart\Criteria\Key\KeyExists;
use Webmozart\Criteria\Key\KeyNotExists;
use Webmozart\Criteria\Logic\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CriterionTest extends PHPUnit_Framework_TestCase
{
    public static function getComparisons()
    {
        return array(
            array(
                'null',
                array(),
                new Null()
            ),
            array(
                'notNull',
                array(),
                new NotNull()
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
                'true',
                array(false),
                new True(false)
            ),
            array(
                'false',
                array(false),
                new False(false)
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
                'oneOf',
                array(array('1', '2', '3'), false),
                new OneOf(array('1', '2', '3'), false)
            ),
        );
    }

    public static function getCriterionTests()
    {
        $criterion = new Null('amount');

        $tests = array(
            array(
                'not',
                array($criterion),
                new Not($criterion),
            ),
            array(
                'key',
                array('field', 'key', $criterion),
                new Key('field', new Key('key', $criterion)),
            ),
            array(
                'keyExists',
                array('field', 'key'),
                new Key('field', new KeyExists('key')),
            ),
            array(
                'keyNotExists',
                array('field', 'key'),
                new Key('field', new KeyNotExists('key')),
            ),
        );

        // Add tests for the field methods
        foreach (self::getComparisons() as $comparisonTest) {
            $tests[] = array(
                $comparisonTest[0],
                array_merge(array('field'), $comparisonTest[1]),
                new Key('field', $comparisonTest[2]),
            );
        }

        // Add tests for the key methods
        foreach (self::getComparisons() as $comparisonTest) {
            if ('is' === substr($comparisonTest[0], 0, 2)) {
                $comparisonTest[0] = substr($comparisonTest[0], 2);
            }

            $tests[] = array(
                'key'.ucfirst($comparisonTest[0]),
                array_merge(array('field', 'key'), $comparisonTest[1]),
                new Key('field', new Key('key', $comparisonTest[2])),
            );
        }

        return $tests;
    }

    /**
     * @dataProvider getCriterionTests
     */
    public function testCreate($method, $args, $expected)
    {
        $this->assertEquals($expected, call_user_func_array(array('Webmozart\Criteria\Criterion', $method), $args));
    }
}
