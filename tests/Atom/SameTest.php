<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Atom;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\OneOf;
use Webmozart\Criteria\Atom\Same;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SameTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Same('amount', '10');

        $this->assertTrue($criterion->match(array('amount' => '10')));
        $this->assertFalse($criterion->match(array('amount' => '100')));
        $this->assertFalse($criterion->match(array('amount' => 11)));
        $this->assertFalse($criterion->match(array('amount' => 10)));
        $this->assertFalse($criterion->match(array('amount' => 10.0)));
    }

    public function testEqualOneOfWithSingleValueIfStrict()
    {
        $criterion = new Same('amount', '10');

        $this->assertTrue($criterion->equals(new OneOf('amount', array('10'), true)));
        $this->assertFalse($criterion->equals(new OneOf('amount', array(10), true)));
        $this->assertFalse($criterion->equals(new OneOf('amount', array('10'), false)));
        $this->assertFalse($criterion->equals(new OneOf('amount', array(), true)));
        $this->assertFalse($criterion->equals(new OneOf('amount', array('10', '11'), true)));
    }
}
