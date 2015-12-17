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
use Webmozart\Expression\Constraint\EndsWith;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Selector\Exactly;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ExactlyTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $exactly1 = new Exactly(1, new GreaterThan(10));
        $exactly2 = new Exactly(2, new GreaterThan(10));

        $this->assertFalse($exactly1->evaluate(array(9, 10, 11, 12)));
        $this->assertTrue($exactly1->evaluate(array(9, 10, 11)));
        $this->assertFalse($exactly1->evaluate(array(9, 10)));
        $this->assertFalse($exactly1->evaluate(new ArrayIterator(array(9, 10, 11, 12))));
        $this->assertTrue($exactly1->evaluate(new ArrayIterator(array(9, 10, 11))));
        $this->assertFalse($exactly1->evaluate(new ArrayIterator(array(9, 10))));

        $this->assertTrue($exactly2->evaluate(array(9, 10, 11, 12)));
        $this->assertFalse($exactly2->evaluate(array(9, 10, 11)));
        $this->assertFalse($exactly2->evaluate(array(9, 10)));
        $this->assertTrue($exactly2->evaluate(new ArrayIterator(array(9, 10, 11, 12))));
        $this->assertFalse($exactly2->evaluate(new ArrayIterator(array(9, 10, 11))));
        $this->assertFalse($exactly2->evaluate(new ArrayIterator(array(9, 10))));

        $this->assertFalse($exactly1->evaluate(array()));
        $this->assertFalse($exactly1->evaluate('foobar'));
    }

    public function testToString()
    {
        $expr1 = new Exactly(1, new GreaterThan(10));
        $expr2 = new Exactly(2, new EndsWith('.css'));
        $expr3 = new Exactly(3, new AndX(array(
            new GreaterThan(10),
            new EndsWith('.css'),
        )));

        $this->assertSame('exactly(1, >10)', $expr1->toString());
        $this->assertSame('exactly(2, endsWith(".css"))', $expr2->toString());
        $this->assertSame('exactly(3, >10 && endsWith(".css"))', $expr3->toString());
    }
}
