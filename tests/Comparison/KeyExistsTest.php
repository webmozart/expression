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
use Webmozart\Expression\Constraint\KeyExists;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyExistsTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $expr = new KeyExists('key');

        $this->assertTrue($expr->evaluate(array('key' => 11)));
        $this->assertFalse($expr->evaluate(array()));
        $this->assertFalse($expr->evaluate('foobar'));
    }

    public function testToString()
    {
        $expr = new KeyExists('key');

        $this->assertSame('keyExists("key")', $expr->toString());
    }
}
