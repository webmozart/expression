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
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Logic\False;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FalseTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new False();

        $this->assertFalse($expr->evaluate(123));
        $this->assertFalse($expr->evaluate(true));
        $this->assertFalse($expr->evaluate(new stdClass()));
    }

    public function testToString()
    {
        $expr = new False();

        $this->assertSame('false', $expr->toString());
    }

    public function testAndReturnsFalse()
    {
        $expr = new False();
        $same = new Same('10');

        $this->assertSame($expr, $expr->andX($same));
    }

    public function testOrReturnsConjunct()
    {
        $expr = new False();
        $same = new Same('10');

        $this->assertSame($same, $expr->orX($same));
    }
}
