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
use Webmozart\Criteria\Comparison\IsEmpty;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IsEmptyTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new IsEmpty();

        $this->assertTrue($criterion->match(null));
        $this->assertTrue($criterion->match(0));
        $this->assertTrue($criterion->match(''));
        $this->assertTrue($criterion->match(false));
        $this->assertFalse($criterion->match(true));
        $this->assertFalse($criterion->match('abcd'));
    }
}
