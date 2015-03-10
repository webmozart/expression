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
use Webmozart\Criteria\Comparison\Null;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NullTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Null();

        $this->assertTrue($criterion->match(null));
        $this->assertFalse($criterion->match(0));
        $this->assertFalse($criterion->match(''));
        $this->assertFalse($criterion->match(false));
    }
}
