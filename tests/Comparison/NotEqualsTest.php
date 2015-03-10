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
use Webmozart\Criteria\Comparison\NotEquals;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotEqualsTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new NotEquals('10');

        $this->assertTrue($criterion->match('100'));
        $this->assertTrue($criterion->match(11));
        $this->assertFalse($criterion->match('10'));
        $this->assertFalse($criterion->match(10));
        $this->assertFalse($criterion->match(10.0));
    }
}
