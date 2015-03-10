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
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Key\Key;
use Webmozart\Expression\Key\KeyExists;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyExistsTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $expr = new KeyExists('key');

        $this->assertTrue($expr->evaluate(array('key' => 11)));
        $this->assertFalse($expr->evaluate(array()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMatchFailsIfNoArray()
    {
        $expr = new Key('key', new GreaterThan(10));

        $expr->evaluate('foobar');
    }
}
