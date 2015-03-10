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
use Webmozart\Expression\Comparison\OneOf;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OneOfTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $expr = new OneOf(array('1', '2', '3'));

        $this->assertTrue($expr->evaluate('1'));
        $this->assertFalse($expr->evaluate(1));
        $this->assertFalse($expr->evaluate(1.0));
        $this->assertFalse($expr->evaluate(0));
        $this->assertFalse($expr->evaluate(10));
        $this->assertFalse($expr->evaluate(null));
    }

    public function testMatchNonStrict()
    {
        $expr = new OneOf(array('1', '2', '3'), false);

        $this->assertTrue($expr->evaluate('1'));
        $this->assertTrue($expr->evaluate(1));
        $this->assertTrue($expr->evaluate(1.0));
        $this->assertFalse($expr->evaluate(0));
        $this->assertFalse($expr->evaluate(10));
        $this->assertFalse($expr->evaluate(null));
    }
}
