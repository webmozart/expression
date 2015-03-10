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
use Webmozart\Criteria\Comparison\True;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TrueTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $criterion = new True();

        $this->assertTrue($criterion->match(true));
        $this->assertFalse($criterion->match(1));
        $this->assertFalse($criterion->match('1'));
        $this->assertFalse($criterion->match(false));
        $this->assertFalse($criterion->match(null));
    }

    public function testMatchNonStrict()
    {
        $criterion = new True(false);

        $this->assertTrue($criterion->match(true));
        $this->assertTrue($criterion->match(1));
        $this->assertTrue($criterion->match('1'));
        $this->assertFalse($criterion->match(false));
        $this->assertFalse($criterion->match(null));
    }
}
