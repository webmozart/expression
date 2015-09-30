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
use Webmozart\Expression\Constraint\Matches;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class MatchesTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Matches('~^\d{4}$~');

        $this->assertTrue($expr->evaluate('1010'));
        $this->assertTrue($expr->evaluate(1010));
        $this->assertFalse($expr->evaluate('abcd'));
        $this->assertFalse($expr->evaluate('10101'));
    }

    public function testToString()
    {
        $expr = new Matches('~^\d{4}$~');

        $this->assertSame('matches("~^\d{4}$~")', $expr->toString());
    }
}
