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
use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\AlwaysFalse;
use Webmozart\Expression\Logic\AlwaysTrue;
use Webmozart\Expression\Logic\Not;
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
class EquivalenceTest extends PHPUnit_Framework_TestCase
{
    public function getEquivalentCriteria()
    {
        return array(
            array(new Same('10'), new Same('10')),
            array(new Same('10'), new In(array('10'), true)),

            array(new NotSame('10'), new NotSame('10')),

            array(new Equals('10'), new Equals('10')),
            array(new Equals('10'), new Equals(10)),
            array(new Equals('10'), new In(array('10'), false)),
            array(new Equals('10'), new In(array(10), false)),

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

            array(new IsInstanceOf('SplFileInfo'), new IsInstanceOf('SplFileInfo')),

            array(new KeyExists('10'), new KeyExists('10')),
            array(new KeyExists('10'), new KeyExists(10)),

            array(new KeyNotExists('10'), new KeyNotExists('10')),
            array(new KeyNotExists('10'), new KeyNotExists(10)),

            array(new Matches('foo.*'), new Matches('foo.*')),

            array(new In(array('10'), false), new In(array('10'), false)),
            array(new In(array('10'), false), new In(array(10), false)),
            array(new In(array('10'), true), new In(array('10'), true)),

            array(new StartsWith('10'), new StartsWith('10')),
            array(new StartsWith('10'), new StartsWith(10)),

            array(new EndsWith('10'), new EndsWith('10')),
            array(new EndsWith('10'), new EndsWith(10)),

            array(new Contains('10'), new Contains('10')),
            array(new Contains('10'), new Contains(10)),

            array(new Not(new Same('10')), new Not(new Same('10'))),

            array(new Key('key', new Same('10')), new Key('key', new Same('10'))),
            array(new Key('42', new Same('10')), new Key(42, new Same('10'))),

            array(new Property('prop', new Same('10')), new Property('prop', new Same('10'))),

            array(new Method('getFoo', array(), new Same('10')), new Method('getFoo', array(), new Same('10'))),
            array(new Method('getFoo', array(42), new Same('10')), new Method('getFoo', array(42), new Same('10'))),

            array(new AtLeast(1, new Same('10')), new AtLeast(1, new Same('10'))),

            array(new AtMost(1, new Same('10')), new AtMost(1, new Same('10'))),

            array(new Exactly(1, new Same('10')), new Exactly(1, new Same('10'))),

            array(new All(new Same('10')), new All(new Same('10'))),

            array(new Count(new Same(10)), new Count(new Same(10))),

            array(new AlwaysTrue(), new AlwaysTrue()),

            array(new AlwaysFalse(), new AlwaysFalse()),
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

            array(new Same('10'), new In(array(10), true)),
            array(new Same('10'), new In(array('10'), false)),
            array(new Same('10'), new In(array(), true)),
            array(new Same('10'), new In(array('10', '11'), true)),

            array(new NotSame('10'), new NotSame('11')),
            array(new NotSame('10'), new NotSame(10)),
            array(new NotSame('10'), new NotEquals('10')),

            array(new Equals('10'), new Equals('11')),
            array(new Equals('10'), new In(array('10'), true)),
            array(new Equals('10'), new In(array(), false)),
            array(new Equals('10'), new In(array('10', '11'), false)),

            array(new GreaterThan('10'), new GreaterThan('11')),
            array(new GreaterThan('10'), new LessThan('10')),

            array(new GreaterThanEqual('10'), new GreaterThanEqual('11')),
            array(new GreaterThanEqual('10'), new LessThan('10')),

            array(new LessThan('10'), new LessThan('11')),
            array(new LessThan('10'), new GreaterThan('10')),

            array(new LessThanEqual('10'), new LessThanEqual('11')),
            array(new LessThanEqual('10'), new GreaterThan('10')),

            array(new IsInstanceOf('SplFileInfo'), new IsInstanceOf('DateTime')),

            array(new KeyExists('10'), new KeyExists('11')),
            array(new KeyExists('foo'), new KeyExists(0)),
            array(new KeyExists('10'), new Equals('10')),

            array(new KeyNotExists('10'), new KeyNotExists('11')),
            array(new KeyNotExists('foo'), new KeyNotExists(0)),
            array(new KeyNotExists('10'), new Equals('10')),

            array(new Matches('10'), new Matches(10)),
            array(new Matches('10'), new Equals('10')),

            array(new In(array('10'), true), new In(array('11'), true)),
            array(new In(array('10'), true), new In(array(10), true)),
            array(new In(array('10'), true), new In(array('11'), false)),
            array(new In(array('10'), true), new IsEmpty()),

            array(new StartsWith('10'), new StartsWith('11')),
            array(new StartsWith('foo'), new StartsWith(0)),
            array(new StartsWith('10'), new Equals('10')),

            array(new EndsWith('10'), new EndsWith('11')),
            array(new EndsWith('foo'), new EndsWith(0)),
            array(new EndsWith('10'), new Equals('10')),

            array(new Contains('10'), new Contains('11')),
            array(new Contains('foo'), new Contains(0)),
            array(new Contains('10'), new Equals('10')),

            array(new Not(new Same('10')), new Not(new Same(10))),
            array(new Not(new Same('10')), new Same(10)),

            array(new Key('foo', new Same('10')), new Key('bar', new Same('10'))),
            array(new Key('foo', new Same('10')), new Key(0, new Same('10'))),
            array(new Key('foo', new Same('10')), new Key('foo', new Same(10))),
            array(new Key('foo', new Same('10')), new Same('10')),

            array(new Property('foo', new Same('10')), new Property('bar', new Same('10'))),
            array(new Property('foo', new Same('10')), new Property('foo', new Same(10))),
            array(new Property('foo', new Same('10')), new Same('10')),

            array(new Method('getFoo', array(42), new Same('10')), new Method('getFoo', array('42'), new Same('10'))),
            array(new Method('getFoo', array(42), new Same('10')), new Method('getFoo', array(42, true), new Same('10'))),
            array(new Method('getFoo', array(), new Same('10')), new Method('getBar', array(), new Same('10'))),
            array(new Method('getFoo', array(), new Same('10')), new Method('getFoo', array(), new Same(10))),
            array(new Method('getFoo', array(), new Same('10')), new Same('10')),

            array(new AtLeast(1, new Same('10')), new AtLeast(2, new Same('10'))),
            array(new AtLeast(1, new Same('10')), new AtLeast(1, new Same(10))),
            array(new AtLeast(1, new Same('10')), new Same('10')),

            array(new AtMost(1, new Same('10')), new AtMost(2, new Same('10'))),
            array(new AtMost(1, new Same('10')), new AtMost(1, new Same(10))),
            array(new AtMost(1, new Same('10')), new Same('10')),

            array(new Exactly(1, new Same('10')), new Exactly(2, new Same('10'))),
            array(new Exactly(1, new Same('10')), new Exactly(1, new Same(10))),
            array(new Exactly(1, new Same('10')), new Same('10')),

            array(new All(new Same('10')), new All(new Same(10))),
            array(new All(new Same('10')), new Same('10')),

            array(new Count(new Same('10')), new Count(new Same(10))),
            array(new Count(new Same('10')), new Same('10')),

            array(new AlwaysTrue(), new Same(10)),

            array(new AlwaysFalse(), new Same(10)),
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
