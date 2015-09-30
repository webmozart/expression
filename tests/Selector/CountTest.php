<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Selector;

use ArrayIterator;
use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Selector\Count;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CountTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $all = new Count(new GreaterThan(1));

        $this->assertTrue($all->evaluate(array(1, 2, 3)));
        $this->assertTrue($all->evaluate(array(1, 2)));
        $this->assertFalse($all->evaluate(array(1)));
        $this->assertTrue($all->evaluate(new ArrayIterator(array(1, 2, 3))));
        $this->assertTrue($all->evaluate(new ArrayIterator(array(1, 2))));
        $this->assertFalse($all->evaluate(new ArrayIterator(array(1))));

        $this->assertFalse($all->evaluate(array()));
        $this->assertFalse($all->evaluate('foobar'));
    }

    public function testToString()
    {
        $expr = new Count(new GreaterThan(10));

        $this->assertSame('count(>10)', $expr->toString());
    }
}
