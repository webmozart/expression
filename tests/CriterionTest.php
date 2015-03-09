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
use Webmozart\Criteria\Atom\EndsWith;
use Webmozart\Criteria\Atom\Equals;
use Webmozart\Criteria\Atom\GreaterThan;
use Webmozart\Criteria\Atom\GreaterThanEqual;
use Webmozart\Criteria\Atom\IsEmpty;
use Webmozart\Criteria\Atom\LessThan;
use Webmozart\Criteria\Atom\LessThanEqual;
use Webmozart\Criteria\Atom\Matches;
use Webmozart\Criteria\Atom\NotEmpty;
use Webmozart\Criteria\Atom\NotEquals;
use Webmozart\Criteria\Atom\NotNull;
use Webmozart\Criteria\Atom\NotSame;
use Webmozart\Criteria\Atom\Null;
use Webmozart\Criteria\Atom\OneOf;
use Webmozart\Criteria\Atom\Same;
use Webmozart\Criteria\Atom\StartsWith;
use Webmozart\Criteria\Criterion;
use Webmozart\Criteria\Literal\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CriterionTest extends PHPUnit_Framework_TestCase
{
    public static function getCriterionTests()
    {
        $criterion = new Null('amount');

        return array(
            array(
                'not',
                array($criterion),
                new Not($criterion)
            ),
            array(
                'null',
                array('amount'),
                new Null('amount')
            ),
            array(
                'notNull',
                array('amount'),
                new NotNull('amount')
            ),
            array(
                'isEmpty',
                array('amount'),
                new IsEmpty('amount')
            ),
            array(
                'notEmpty',
                array('amount'),
                new NotEmpty('amount')
            ),
            array(
                'equals',
                array('amount', 10),
                new Equals('amount', 10)
            ),
            array(
                'notEquals',
                array('amount', 10),
                new NotEquals('amount', 10)
            ),
            array(
                'same',
                array('amount', 10),
                new Same('amount', 10)
            ),
            array(
                'notSame',
                array('amount', 10),
                new NotSame('amount', 10)
            ),
            array(
                'greaterThan',
                array('amount', 10),
                new GreaterThan('amount', 10)
            ),
            array(
                'greaterThanEqual',
                array('amount', 10),
                new GreaterThanEqual('amount', 10)
            ),
            array(
                'lessThan',
                array('amount', 10),
                new LessThan('amount', 10)
            ),
            array(
                'lessThanEqual',
                array('amount', 10),
                new LessThanEqual('amount', 10)
            ),
            array(
                'matches',
                array('postalCode', '~^\d{4}$~'),
                new Matches('postalCode', '~^\d{4}$~')
            ),
            array(
                'startsWith',
                array('name', 'Thomas'),
                new StartsWith('name', 'Thomas')
            ),
            array(
                'endsWith',
                array('filename', '.css'),
                new EndsWith('filename', '.css')
            ),
            array(
                'oneOf',
                array('amount', array('1', '2', '3'), false),
                new OneOf('amount', array('1', '2', '3'), false)
            ),
        );
    }

    /**
     * @dataProvider getCriterionTests
     */
    public function testCreate($method, $args, $expected)
    {
        $this->assertEquals($expected, call_user_func_array(array('Webmozart\Criteria\Criterion', $method), $args));
    }

}
