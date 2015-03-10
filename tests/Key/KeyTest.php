<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Key;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\LessThan;
use Webmozart\Expression\Key\Key;
use Webmozart\Expression\Logic\Disjunction;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $expr = new Key('key', new GreaterThan(10));

        $this->assertTrue($expr->evaluate(array('key' => 11)));
        $this->assertFalse($expr->evaluate(array('key' => 9)));
    }

    public function testMatchReturnsFalseIfKeyNotFound()
    {
        $expr = new Key('key', new GreaterThan(10));

        $this->assertFalse($expr->evaluate(array()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMatchFailsIfNoArray()
    {
        $expr = new Key('key', new GreaterThan(10));

        $expr->evaluate('foobar');
    }

    public function testEquals()
    {
        $expr1 = new Key('key', new Disjunction(array(new LessThan(0), new GreaterThan(10))));
        $expr2 = new Key('key', new Disjunction(array(new GreaterThan(10), new LessThan(0))));
        $expr3 = new Key('other', new Disjunction(array(new LessThan(0), new GreaterThan(10))));
        $expr4 = new Key('key', new Disjunction(array(new LessThan(0))));

        $this->assertTrue($expr1->equals($expr2));
        $this->assertTrue($expr2->equals($expr1));

        $this->assertFalse($expr1->equals($expr3));
        $this->assertFalse($expr3->equals($expr1));

        $this->assertFalse($expr1->equals($expr4));
        $this->assertFalse($expr4->equals($expr1));

        $this->assertFalse($expr2->equals($expr3));
        $this->assertFalse($expr3->equals($expr2));

        $this->assertFalse($expr2->equals($expr4));
        $this->assertFalse($expr4->equals($expr2));

        $this->assertFalse($expr3->equals($expr4));
        $this->assertFalse($expr4->equals($expr3));
    }
}
