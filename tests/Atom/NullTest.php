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
use Webmozart\Criteria\Atom\Null;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NullTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Null('amount');

        $this->assertTrue($criterion->match(array('amount' => null)));
        $this->assertFalse($criterion->match(array('amount' => 0)));
        $this->assertFalse($criterion->match(array('amount' => '')));
        $this->assertFalse($criterion->match(array('amount' => false)));
    }
}
