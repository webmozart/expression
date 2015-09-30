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
use stdClass;
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Logic\AlwaysTrue;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class AlwaysTrueTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new AlwaysTrue();

        $this->assertTrue($expr->evaluate(123));
        $this->assertTrue($expr->evaluate(true));
        $this->assertTrue($expr->evaluate(new stdClass()));
    }

    public function testToString()
    {
        $expr = new AlwaysTrue();

        $this->assertSame('true', $expr->toString());
    }

    public function testAndReturnsConjunct()
    {
        $expr = new AlwaysTrue();
        $same = new Same('10');

        $this->assertSame($same, $expr->andX($same));
    }

    public function testOrReturnsTrue()
    {
        $expr = new AlwaysTrue();
        $same = new Same('10');

        $this->assertSame($expr, $expr->orX($same));
    }
}
