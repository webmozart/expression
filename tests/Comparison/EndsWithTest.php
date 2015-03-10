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
use Webmozart\Criteria\Comparison\EndsWith;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EndsWithTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new EndsWith('.css');

        $this->assertTrue($criterion->match('style.css'));
        $this->assertFalse($criterion->match('style.css.dist'));
    }
}
