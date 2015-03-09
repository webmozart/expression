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
use Webmozart\Criteria\Atom\False;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FalseTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $criterion = new False('enabled');

        $this->assertTrue($criterion->match(array('enabled' => false)));
        $this->assertFalse($criterion->match(array('enabled' => 0)));
        $this->assertFalse($criterion->match(array('enabled' => '')));
        $this->assertFalse($criterion->match(array('enabled' => null)));
        $this->assertFalse($criterion->match(array('enabled' => 1)));
        $this->assertFalse($criterion->match(array('enabled' => true)));
    }

    public function testMatchNonStrict()
    {
        $criterion = new False('enabled', false);

        $this->assertTrue($criterion->match(array('enabled' => false)));
        $this->assertTrue($criterion->match(array('enabled' => 0)));
        $this->assertTrue($criterion->match(array('enabled' => '')));
        $this->assertTrue($criterion->match(array('enabled' => null)));
        $this->assertFalse($criterion->match(array('enabled' => 1)));
        $this->assertFalse($criterion->match(array('enabled' => true)));
    }
}
