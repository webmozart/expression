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
use Webmozart\Expression\Constraint\IsEmpty;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IsEmptyTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new IsEmpty();

        $this->assertTrue($expr->evaluate(null));
        $this->assertTrue($expr->evaluate(0));
        $this->assertTrue($expr->evaluate(''));
        $this->assertTrue($expr->evaluate(false));
        $this->assertFalse($expr->evaluate(true));
        $this->assertFalse($expr->evaluate('abcd'));
    }

    public function testToString()
    {
        $expr = new IsEmpty();

        $this->assertSame('empty()', $expr->toString());
    }
}
