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
use Webmozart\Criteria\Atom\True;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TrueTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $criterion = new True('enabled');

        $this->assertTrue($criterion->match(array('enabled' => true)));
        $this->assertFalse($criterion->match(array('enabled' => 1)));
        $this->assertFalse($criterion->match(array('enabled' => '1')));
        $this->assertFalse($criterion->match(array('enabled' => false)));
        $this->assertFalse($criterion->match(array('enabled' => null)));
    }

    public function testMatchNonStrict()
    {
        $criterion = new True('enabled', false);

        $this->assertTrue($criterion->match(array('enabled' => true)));
        $this->assertTrue($criterion->match(array('enabled' => 1)));
        $this->assertTrue($criterion->match(array('enabled' => '1')));
        $this->assertFalse($criterion->match(array('enabled' => false)));
        $this->assertFalse($criterion->match(array('enabled' => null)));
    }
}
