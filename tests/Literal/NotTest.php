<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Literal;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\StartsWith;
use Webmozart\Criteria\Literal\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Not(new StartsWith('name', 'Thomas'));

        $this->assertTrue($criterion->match(array('name' => 'Mr. Thomas Edison')));
        $this->assertFalse($criterion->match(array('name' => 'Thomas Edison')));
    }
}
