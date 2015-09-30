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

use DirectoryIterator;
use PHPUnit_Framework_TestCase;
use SplFileInfo;
use Webmozart\Expression\Constraint\IsInstanceOf;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IsInstanceOfTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new IsInstanceOf('SplFileInfo');

        $this->assertTrue($expr->evaluate(new SplFileInfo(__DIR__)));
        $this->assertTrue($expr->evaluate(new DirectoryIterator(__DIR__)));
        $this->assertFalse($expr->evaluate((object) array()));
        $this->assertFalse($expr->evaluate(array()));
        $this->assertFalse($expr->evaluate('foobar'));
    }

    public function testToString()
    {
        $expr = new IsInstanceOf('SplFileInfo');

        $this->assertSame('instanceOf(SplFileInfo)', $expr->toString());
    }
}
