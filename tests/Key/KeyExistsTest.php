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
use Webmozart\Criteria\Comparison\GreaterThan;
use Webmozart\Criteria\Key\Key;
use Webmozart\Criteria\Key\KeyExists;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyExistsTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new KeyExists('key');

        $this->assertTrue($criterion->match(array('key' => 11)));
        $this->assertFalse($criterion->match(array()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMatchFailsIfNoArray()
    {
        $criterion = new Key('key', new GreaterThan(10));

        $criterion->match('foobar');
    }
}
