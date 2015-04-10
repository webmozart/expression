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
use Webmozart\Expression\Logic\Valid;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ValidTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Valid();

        $this->assertTrue($expr->evaluate(123));
        $this->assertTrue($expr->evaluate(true));
        $this->assertTrue($expr->evaluate(new stdClass()));
    }

    public function testToString()
    {
        $expr = new Valid();

        $this->assertSame('valid', $expr->toString());
    }
}
