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
use Webmozart\Expression\Comparison\NotEmpty;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotEmptyTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new NotEmpty();

        $this->assertTrue($expr->evaluate(true));
        $this->assertTrue($expr->evaluate('abcd'));
        $this->assertFalse($expr->evaluate(null));
        $this->assertFalse($expr->evaluate(0));
        $this->assertFalse($expr->evaluate(''));
        $this->assertFalse($expr->evaluate(false));
    }

    public function testToString()
    {
        $expr = new NotEmpty();

        $this->assertSame('notEmpty()', $expr->toString());
    }
}
