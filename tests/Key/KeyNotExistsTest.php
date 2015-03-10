<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Key;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Key\KeyNotExists;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyNotExistsTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new KeyNotExists('key');

        $this->assertTrue($criterion->match(array()));
        $this->assertFalse($criterion->match(array('key' => 11)));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMatchFailsIfNoArray()
    {
        $criterion = new KeyNotExists('key');

        $criterion->match('foobar');
    }
}
