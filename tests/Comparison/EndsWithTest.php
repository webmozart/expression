<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Comparison;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Constraint\EndsWith;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EndsWithTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new EndsWith('.css');

        $this->assertTrue($expr->evaluate('style.css'));
        $this->assertFalse($expr->evaluate('style.css.dist'));
    }

    public function testToString()
    {
        $expr = new EndsWith('.css');

        $this->assertSame('endsWith(".css")', $expr->toString());
    }
}
