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
use Webmozart\Expression\Selector\AtLeast;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class AtLeastTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $atLeast1 = new AtLeast(1, new GreaterThan(10));
        $atLeast2 = new AtLeast(2, new GreaterThan(10));

        $this->assertTrue($atLeast1->evaluate(array(9, 10, 11, 12)));
        $this->assertTrue($atLeast1->evaluate(array(9, 10, 11)));
        $this->assertFalse($atLeast1->evaluate(array(9, 10)));
        $this->assertTrue($atLeast1->evaluate(new ArrayIterator(array(9, 10, 11, 12))));
        $this->assertTrue($atLeast1->evaluate(new ArrayIterator(array(9, 10, 11))));
        $this->assertFalse($atLeast1->evaluate(new ArrayIterator(array(9, 10))));

        $this->assertTrue($atLeast2->evaluate(array(9, 10, 11, 12)));
        $this->assertFalse($atLeast2->evaluate(array(9, 10, 11)));
        $this->assertFalse($atLeast2->evaluate(array(9, 10)));
        $this->assertTrue($atLeast2->evaluate(new ArrayIterator(array(9, 10, 11, 12))));
        $this->assertFalse($atLeast2->evaluate(new ArrayIterator(array(9, 10, 11))));
        $this->assertFalse($atLeast2->evaluate(new ArrayIterator(array(9, 10))));

        $this->assertFalse($atLeast1->evaluate(array()));
        $this->assertFalse($atLeast1->evaluate('foobar'));
    }

    public function testToString()
    {
        $expr1 = new AtLeast(1, new GreaterThan(10));
        $expr2 = new AtLeast(2, new EndsWith('.css'));
        $expr3 = new AtLeast(3, new AndX(array(
            new GreaterThan(10),
            new EndsWith('.css'),
        )));

        $this->assertSame('atLeast(1, >10)', $expr1->toString());
        $this->assertSame('atLeast(2, endsWith(".css"))', $expr2->toString());
        $this->assertSame('atLeast(3, >10 && endsWith(".css"))', $expr3->toString());
    }
}
