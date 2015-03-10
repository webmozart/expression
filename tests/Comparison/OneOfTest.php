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
use Webmozart\Criteria\Comparison\OneOf;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OneOfTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $criterion = new OneOf(array('1', '2', '3'));

        $this->assertTrue($criterion->match('1'));
        $this->assertFalse($criterion->match(1));
        $this->assertFalse($criterion->match(1.0));
        $this->assertFalse($criterion->match(0));
        $this->assertFalse($criterion->match(10));
        $this->assertFalse($criterion->match(null));
    }

    public function testMatchNonStrict()
    {
        $criterion = new OneOf(array('1', '2', '3'), false);

        $this->assertTrue($criterion->match('1'));
        $this->assertTrue($criterion->match(1));
        $this->assertTrue($criterion->match(1.0));
        $this->assertFalse($criterion->match(0));
        $this->assertFalse($criterion->match(10));
        $this->assertFalse($criterion->match(null));
    }
}
