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
use Webmozart\Criteria\Atom\Equals;
use Webmozart\Criteria\Atom\OneOf;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EqualsTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Equals('amount', '10');

        $this->assertTrue($criterion->match(array('amount' => '10')));
        $this->assertTrue($criterion->match(array('amount' => 10)));
        $this->assertTrue($criterion->match(array('amount' => 10.0)));
        $this->assertFalse($criterion->match(array('amount' => '100')));
        $this->assertFalse($criterion->match(array('amount' => 11)));
    }
}
