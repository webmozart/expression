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
use Webmozart\Expression\Comparison\Equals;
use Webmozart\Expression\Comparison\OneOf;
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Expression;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EquivalenceTest extends PHPUnit_Framework_TestCase
{
    public function getEquivalentCriteria()
    {
        return array(
            array(new Same('10'), new OneOf(array('10'), true)),

            array(new Equals('10'), new OneOf(array('10'), false)),
            array(new Equals('10'), new OneOf(array(10), false)),
        );
    }

    /**
     * @dataProvider getEquivalentCriteria
     */
    public function testEquivalence(Expression $left, Expression $right)
    {
        $this->assertTrue($left->equals($right));
        $this->assertTrue($right->equals($left));
    }

    public function getNonEquivalentCriteria()
    {
        return array(
            array(new Same('10'), new OneOf(array(10), true)),
            array(new Same('10'), new OneOf(array('10'), false)),
            array(new Same('10'), new OneOf(array(), true)),
            array(new Same('10'), new OneOf(array('10', '11'), true)),

            array(new Equals('10'), new OneOf(array('10'), true)),
            array(new Equals('10'), new OneOf(array(), false)),
            array(new Equals('10'), new OneOf(array('10', '11'), false)),
        );
    }

    /**
     * @dataProvider getNonEquivalentCriteria
     */
    public function testNonEquivalence(Expression $left, Expression $right)
    {
        $this->assertFalse($left->equals($right));
        $this->assertFalse($right->equals($left));
    }
}
