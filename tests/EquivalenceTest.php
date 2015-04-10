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
use Webmozart\Expression\Comparison\OneOf;
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Comparison\StartsWith;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Selector\AtLeast;
use Webmozart\Expression\Selector\Key;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EquivalenceTest extends PHPUnit_Framework_TestCase
{
    public function getEquivalentCriteria()
    {
        return array(
            array(new Same('10'), new Same('10')),
            array(new Same('10'), new OneOf(array('10'), true)),

            array(new NotSame('10'), new NotSame('10')),

            array(new Equals('10'), new Equals('10')),
            array(new Equals('10'), new Equals(10)),
            array(new Equals('10'), new OneOf(array('10'), false)),
            array(new Equals('10'), new OneOf(array(10), false)),

            array(new NotEquals('10'), new NotEquals('10')),
            array(new NotEquals('10'), new NotEquals(10)),

            array(new GreaterThan('10'), new GreaterThan('10')),
            array(new GreaterThan('10'), new GreaterThan(10)),

            array(new GreaterThanEqual('10'), new GreaterThanEqual('10')),
            array(new GreaterThanEqual('10'), new GreaterThanEqual(10)),

            array(new LessThan('10'), new LessThan('10')),
            array(new LessThan('10'), new LessThan(10)),

            array(new LessThanEqual('10'), new LessThanEqual('10')),
            array(new LessThanEqual('10'), new LessThanEqual(10)),

            array(new IsEmpty(), new IsEmpty()),

            array(new NotEmpty(), new NotEmpty()),

            array(new KeyExists('10'), new KeyExists('10')),
            array(new KeyExists('10'), new KeyExists(10)),

            array(new KeyNotExists('10'), new KeyNotExists('10')),
            array(new KeyNotExists('10'), new KeyNotExists(10)),

            array(new Matches('foo.*'), new Matches('foo.*')),

            array(new OneOf(array('10'), false), new OneOf(array('10'), false)),
            array(new OneOf(array('10'), false), new OneOf(array(10), false)),
            array(new OneOf(array('10'), true), new OneOf(array('10'), true)),

            array(new StartsWith('10'), new StartsWith('10')),
            array(new StartsWith('10'), new StartsWith(10)),

            array(new EndsWith('10'), new EndsWith('10')),
            array(new EndsWith('10'), new EndsWith(10)),

            array(new Not(new Same('10')), new Not(new Same('10'))),

            array(new Key('key', new Same('10')), new Key('key', new Same('10'))),
            array(new Key('42', new Same('10')), new Key(42, new Same('10'))),

            array(new AtLeast(1, new Same('10')), new AtLeast(1, new Same('10'))),
        );
    }

    /**
     * @dataProvider getEquivalentCriteria
     */
    public function testEquivalence(Expression $left, Expression $right)
    {
        $this->assertTrue($left->equivalentTo($right));
        $this->assertTrue($right->equivalentTo($left));
    }

    public function getNonEquivalentCriteria()
    {
        return array(
            array(new Same('10'), new Same('11')),
            array(new Same('10'), new Same(10)),
            array(new Same('10'), new Equals('10')),

            array(new Same('10'), new OneOf(array(10), true)),
            array(new Same('10'), new OneOf(array('10'), false)),
            array(new Same('10'), new OneOf(array(), true)),
            array(new Same('10'), new OneOf(array('10', '11'), true)),

            array(new NotSame('10'), new NotSame('11')),
            array(new NotSame('10'), new NotSame(10)),
            array(new NotSame('10'), new NotEquals('10')),

            array(new Equals('10'), new Equals('11')),
            array(new Equals('10'), new OneOf(array('10'), true)),
            array(new Equals('10'), new OneOf(array(), false)),
            array(new Equals('10'), new OneOf(array('10', '11'), false)),

            array(new GreaterThan('10'), new GreaterThan('11')),
            array(new GreaterThan('10'), new LessThan('10')),

            array(new GreaterThanEqual('10'), new GreaterThanEqual('11')),
            array(new GreaterThanEqual('10'), new LessThan('10')),

            array(new LessThan('10'), new LessThan('11')),
            array(new LessThan('10'), new GreaterThan('10')),

            array(new LessThanEqual('10'), new LessThanEqual('11')),
            array(new LessThanEqual('10'), new GreaterThan('10')),

            array(new IsEmpty(), new NotEmpty()),

            array(new KeyExists('10'), new KeyExists('11')),
            array(new KeyExists('10'), new Equals('10')),

            array(new KeyNotExists('10'), new KeyNotExists('11')),
            array(new KeyNotExists('10'), new Equals('10')),

            array(new Matches('10'), new Matches(10)),
            array(new Matches('10'), new Equals('10')),

            array(new OneOf(array('10'), true), new OneOf(array('11'), true)),
            array(new OneOf(array('10'), true), new OneOf(array(10), true)),
            array(new OneOf(array('10'), true), new OneOf(array('11'), false)),
            array(new OneOf(array('10'), true), new IsEmpty()),

            array(new StartsWith('10'), new StartsWith('11')),
            array(new StartsWith('10'), new Equals('10')),

            array(new EndsWith('10'), new EndsWith('11')),
            array(new EndsWith('10'), new Equals('10')),

            array(new Not(new Same('10')), new Not(new Same(10))),
            array(new Not(new Same('10')), new Same(10)),

            array(new Key('foo', new Same('10')), new Key('bar', new Same('10'))),
            array(new Key('foo', new Same('10')), new Key('foo', new Same(10))),
            array(new Key('foo', new Same('10')), new Same('10')),

            array(new AtLeast(1, new Same('10')), new AtLeast(2, new Same('10'))),
            array(new AtLeast(1, new Same('10')), new AtLeast(1, new Same(10))),
            array(new AtLeast(1, new Same('10')), new Same('10')),
        );
    }

    /**
     * @dataProvider getNonEquivalentCriteria
     */
    public function testNonEquivalence(Expression $left, Expression $right)
    {
        $this->assertFalse($left->equivalentTo($right));
        $this->assertFalse($right->equivalentTo($left));
    }
}
