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
use Webmozart\Expression\Logic\Invalid;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class InvalidTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Invalid();

        $this->assertFalse($expr->evaluate(123));
        $this->assertFalse($expr->evaluate(true));
        $this->assertFalse($expr->evaluate(new stdClass()));
    }

    public function testToString()
    {
        $expr = new Invalid();

        $this->assertSame('invalid', $expr->toString());
    }
}
