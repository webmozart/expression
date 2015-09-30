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
use Webmozart\Expression\Constraint\Contains;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ContainsTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Contains('test');

        $this->assertTrue($expr->evaluate('testString'));
        $this->assertTrue($expr->evaluate('anothertest'));
        $this->assertTrue($expr->evaluate('testtest'));
        $this->assertTrue($expr->evaluate('test'));
        $this->assertFalse($expr->evaluate('xest'));
        $this->assertFalse($expr->evaluate('est'));
        $this->assertFalse($expr->evaluate('tesx'));
    }

    public function testToString()
    {
        $expr = new Contains('testString');

        $this->assertSame('contains("testString")', $expr->toString());
    }
}
