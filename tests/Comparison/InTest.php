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
use Webmozart\Expression\Constraint\In;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class InTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluateStrict()
    {
        $expr = new In(array('1', '2', '3'));

        $this->assertTrue($expr->evaluate('1'));
        $this->assertFalse($expr->evaluate(1));
        $this->assertFalse($expr->evaluate(1.0));
        $this->assertFalse($expr->evaluate(0));
        $this->assertFalse($expr->evaluate(10));
        $this->assertFalse($expr->evaluate(null));
    }

    public function testEvaluateNonStrict()
    {
        $expr = new In(array('1', '2', '3'), false);

        $this->assertTrue($expr->evaluate('1'));
        $this->assertTrue($expr->evaluate(1));
        $this->assertTrue($expr->evaluate(1.0));
        $this->assertFalse($expr->evaluate(0));
        $this->assertFalse($expr->evaluate(10));
        $this->assertFalse($expr->evaluate(null));
    }

    public function testToString()
    {
        $expr = new In(array('1', '2', '3'), false);

        $this->assertSame('in("1", "2", "3")', $expr->toString());
    }
}
