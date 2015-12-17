<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Logic;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Constraint\LessThan;
use Webmozart\Expression\Constraint\StartsWith;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\OrX;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Not(new StartsWith('Thomas'));

        $this->assertTrue($expr->evaluate('Mr. Thomas Edison'));
        $this->assertFalse($expr->evaluate('Thomas Edison'));
    }

    public function testEquivalentTo()
    {
        $expr1 = new Not(new OrX(array(new LessThan(0), new GreaterThan(10))));
        $expr2 = new Not(new OrX(array(new GreaterThan(10), new LessThan(0))));
        $expr3 = new Not(new OrX(array(new GreaterThan(10))));

        $this->assertTrue($expr1->equivalentTo($expr2));
        $this->assertFalse($expr2->equivalentTo($expr3));
        $this->assertFalse($expr1->equivalentTo($expr3));
    }

    public function testToString()
    {
        $expr1 = new Not(new StartsWith('Thomas'));
        $expr2 = new Not(new OrX(array(new GreaterThan(10), new LessThan(0))));

        $this->assertSame('not(startsWith("Thomas"))', $expr1->toString());
        $this->assertSame('not(>10 || <0)', $expr2->toString());
    }
}
