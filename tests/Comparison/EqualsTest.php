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
use Webmozart\Expression\Constraint\Equals;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EqualsTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Equals('10');

        $this->assertTrue($expr->evaluate('10'));
        $this->assertTrue($expr->evaluate(10));
        $this->assertTrue($expr->evaluate(10.0));
        $this->assertFalse($expr->evaluate('100'));
        $this->assertFalse($expr->evaluate(11));
    }

    public function testToString()
    {
        $expr = new Equals('10');

        $this->assertSame('=="10"', $expr->toString());
    }
}
