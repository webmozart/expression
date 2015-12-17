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
use Webmozart\Expression\Constraint\EndsWith;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Selector\Property;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class PropertyTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new Property('prop', new GreaterThan(10));

        $this->assertTrue($expr->evaluate((object) array('prop' => 11)));
        $this->assertFalse($expr->evaluate((object) array('prop' => 9)));
        $this->assertFalse($expr->evaluate((object) array()));
        $this->assertFalse($expr->evaluate(array()));
        $this->assertFalse($expr->evaluate('foobar'));
    }

    public function testToString()
    {
        $expr1 = new Property('prop', new GreaterThan(10));
        $expr2 = new Property('prop', new EndsWith('.css'));
        $expr3 = new Property('prop', new AndX(array(
            new GreaterThan(10),
            new EndsWith('.css'),
        )));

        $this->assertSame('prop>10', $expr1->toString());
        $this->assertSame('prop.endsWith(".css")', $expr2->toString());
        $this->assertSame('prop{>10 && endsWith(".css")}', $expr3->toString());
    }
}
