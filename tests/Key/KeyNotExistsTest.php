<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Key;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Key\KeyNotExists;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyNotExistsTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $expr = new KeyNotExists('key');

        $this->assertTrue($expr->evaluate(array()));
        $this->assertFalse($expr->evaluate(array('key' => 11)));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMatchFailsIfNoArray()
    {
        $expr = new KeyNotExists('key');

        $expr->evaluate('foobar');
    }
}
