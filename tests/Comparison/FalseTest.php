<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Comparison;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Comparison\False;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FalseTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $criterion = new False();

        $this->assertTrue($criterion->match(false));
        $this->assertFalse($criterion->match(0));
        $this->assertFalse($criterion->match(''));
        $this->assertFalse($criterion->match(null));
        $this->assertFalse($criterion->match(1));
        $this->assertFalse($criterion->match(true));
    }

    public function testMatchNonStrict()
    {
        $criterion = new False(false);

        $this->assertTrue($criterion->match(false));
        $this->assertTrue($criterion->match(0));
        $this->assertTrue($criterion->match(''));
        $this->assertTrue($criterion->match(null));
        $this->assertFalse($criterion->match(1));
        $this->assertFalse($criterion->match(true));
    }
}
