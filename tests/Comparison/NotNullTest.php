<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Comparison;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\NotNull;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotNullTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $expr = new NotNull();

        $this->assertTrue($expr->evaluate(0));
        $this->assertTrue($expr->evaluate(''));
        $this->assertTrue($expr->evaluate(false));
        $this->assertFalse($expr->evaluate(null));
    }
}
