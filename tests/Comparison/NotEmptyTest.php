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
use Webmozart\Criteria\Comparison\NotEmpty;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotEmptyTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new NotEmpty();

        $this->assertTrue($criterion->match(true));
        $this->assertTrue($criterion->match('abcd'));
        $this->assertFalse($criterion->match(null));
        $this->assertFalse($criterion->match(0));
        $this->assertFalse($criterion->match(''));
        $this->assertFalse($criterion->match(false));
    }
}
