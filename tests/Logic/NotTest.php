<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Logic;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\LessThan;
use Webmozart\Expression\Comparison\StartsWith;
use Webmozart\Expression\Logic\Disjunction;
use Webmozart\Expression\Logic\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $expr = new Not(new StartsWith('Thomas'));

        $this->assertTrue($expr->evaluate('Mr. Thomas Edison'));
        $this->assertFalse($expr->evaluate('Thomas Edison'));
    }

    public function testEquals()
    {
        $expr1 = new Not(new Disjunction(array(new LessThan(0), new GreaterThan(10))));
        $expr2 = new Not(new Disjunction(array(new GreaterThan(10), new LessThan(0))));
        $expr3 = new Not(new Disjunction(array(new GreaterThan(10))));

        $this->assertTrue($expr1->equals($expr2));
        $this->assertFalse($expr2->equals($expr3));
        $this->assertFalse($expr1->equals($expr3));
    }
}
