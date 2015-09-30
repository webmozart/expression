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
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Tests\Selector\Fixtures\TestSelector;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SelectorTest extends PHPUnit_Framework_TestCase
{
    public function testGetExpression()
    {
        $selector = new TestSelector('key', $expr = new Same('10'));

        $this->assertSame($expr, $selector->getExpression());
    }

    public function testEquivalentTo()
    {
        // "key" is ignored in the dummy implementation
        $selector1 = new TestSelector('key', new Same('10'));
        $selector2 = new TestSelector('key', new Same('10'));
        $selector3 = new TestSelector('key', new Same(10));

        $this->assertTrue($selector1->equivalentTo($selector2));
        $this->assertTrue($selector2->equivalentTo($selector1));
        $this->assertFalse($selector1->equivalentTo($selector3));
        $this->assertFalse($selector3->equivalentTo($selector1));
        $this->assertFalse($selector2->equivalentTo($selector3));
        $this->assertFalse($selector3->equivalentTo($selector2));

        $this->assertFalse($selector1->equivalentTo(new Same('10')));
    }
}
