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

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\EndsWith;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Logic\Conjunction;
use Webmozart\Expression\Selector\AtLeastOne;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class AtLeastOneTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new AtLeastOne(new GreaterThan(10));

        $this->assertTrue($expr->evaluate(array(9, 10, 11, 12)));
        $this->assertTrue($expr->evaluate(array(9, 10, 11)));
        $this->assertFalse($expr->evaluate(array(9, 10)));
        $this->assertFalse($expr->evaluate(array()));
        $this->assertFalse($expr->evaluate('foobar'));
    }

    public function testToString()
    {
        $expr1 = new AtLeastOne(new GreaterThan(10));
        $expr2 = new AtLeastOne(new EndsWith('.css'));
        $expr3 = new AtLeastOne(new Conjunction(array(
            new GreaterThan(10),
            new EndsWith('.css'),
        )));

        $this->assertSame('atLeastOne(>10)', $expr1->toString());
        $this->assertSame('atLeastOne(endsWith(".css"))', $expr2->toString());
        $this->assertSame('atLeastOne(>10 && endsWith(".css"))', $expr3->toString());
    }
}
