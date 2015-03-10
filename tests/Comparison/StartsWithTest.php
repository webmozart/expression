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
use Webmozart\Criteria\Comparison\StartsWith;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class StartsWithTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new StartsWith('Thomas');

        $this->assertTrue($criterion->match('Thomas Edison'));
        $this->assertFalse($criterion->match('Mr. Thomas Edison'));
    }
}
