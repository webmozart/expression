<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Literal;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\GreaterThan;
use Webmozart\Criteria\Atom\Null;
use Webmozart\Criteria\Atom\StartsWith;
use Webmozart\Criteria\Formula\Conjunction;
use Webmozart\Criteria\Literal\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Not(new StartsWith('name', 'Thomas'));

        $this->assertTrue($criterion->match(array('name' => 'Mr. Thomas Edison')));
        $this->assertFalse($criterion->match(array('name' => 'Thomas Edison')));
    }

    public function testEquals()
    {
        $criterion1 = new Not(new Conjunction(array(new Null('field'), new GreaterThan('field', 10))));
        $criterion2 = new Not(new Conjunction(array(new GreaterThan('field', 10), new Null('field'))));
        $criterion3 = new Not(new Conjunction(array(new Null('field'))));

        $this->assertTrue($criterion1->equals($criterion2));
        $this->assertFalse($criterion2->equals($criterion3));
        $this->assertFalse($criterion1->equals($criterion3));
    }
}
