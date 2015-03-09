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
use Webmozart\Criteria\Atom\Equals;
use Webmozart\Criteria\Atom\OneOf;
use Webmozart\Criteria\Atom\Same;
use Webmozart\Criteria\Criteria;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EquivalenceTest extends PHPUnit_Framework_TestCase
{
    public function getEquivalentCriteria()
    {
        return array(
            array(new Same('field', '10'), new OneOf('field', array('10'), true)),

            array(new Equals('field', '10'), new OneOf('field', array('10'), false)),
            array(new Equals('field', '10'), new OneOf('field', array(10), false)),
        );
    }

    /**
     * @dataProvider getEquivalentCriteria
     */
    public function testEquivalence(Criteria $left, Criteria $right)
    {
        $this->assertTrue($left->equals($right));
        $this->assertTrue($right->equals($left));
    }

    public function getNonEquivalentCriteria()
    {
        return array(
            array(new Same('field', '10'), new OneOf('other', array('10'), true)),
            array(new Same('field', '10'), new OneOf('field', array(10), true)),
            array(new Same('field', '10'), new OneOf('field', array('10'), false)),
            array(new Same('field', '10'), new OneOf('field', array(), true)),
            array(new Same('field', '10'), new OneOf('field', array('10', '11'), true)),

            array(new Equals('field', '10'), new OneOf('other', array('10'), false)),
            array(new Equals('field', '10'), new OneOf('field', array('10'), true)),
            array(new Equals('field', '10'), new OneOf('field', array(), false)),
            array(new Equals('field', '10'), new OneOf('field', array('10', '11'), false)),
        );
    }

    /**
     * @dataProvider getNonEquivalentCriteria
     */
    public function testNonEquivalence(Criteria $left, Criteria $right)
    {
        $this->assertFalse($left->equals($right));
        $this->assertFalse($right->equals($left));
    }
}
