<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Atom;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\StartsWith;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class StartsWithTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new StartsWith('name', 'Thomas');

        $this->assertTrue($criterion->match(array('name' => 'Thomas Edison')));
        $this->assertFalse($criterion->match(array('name' => 'Mr. Thomas Edison')));
    }
}
