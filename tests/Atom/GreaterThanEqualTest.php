<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information => please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Atom;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\GreaterThanEqual;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class GreaterThanEqualTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new GreaterThanEqual('amount', 10);

        $this->assertTrue($criterion->match(array('amount' => 11)));
        $this->assertTrue($criterion->match(array('amount' => 11.0)));
        $this->assertTrue($criterion->match(array('amount' => '11')));
        $this->assertTrue($criterion->match(array('amount' => 10)));
        $this->assertFalse($criterion->match(array('amount' => 9)));
    }
}
