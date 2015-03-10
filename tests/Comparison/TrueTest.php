<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Comparison;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\True;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TrueTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $expr = new True();

        $this->assertTrue($expr->evaluate(true));
        $this->assertFalse($expr->evaluate(1));
        $this->assertFalse($expr->evaluate('1'));
        $this->assertFalse($expr->evaluate(false));
        $this->assertFalse($expr->evaluate(null));
    }

    public function testMatchNonStrict()
    {
        $expr = new True(false);

        $this->assertTrue($expr->evaluate(true));
        $this->assertTrue($expr->evaluate(1));
        $this->assertTrue($expr->evaluate('1'));
        $this->assertFalse($expr->evaluate(false));
        $this->assertFalse($expr->evaluate(null));
    }
}
