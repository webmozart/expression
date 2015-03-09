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
use Webmozart\Criteria\Atom\Matches;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class MatchesTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Matches('postalCode', '~^\d{4}$~');

        $this->assertTrue($criterion->match(array('postalCode' => '1010')));
        $this->assertTrue($criterion->match(array('postalCode' => 1010)));
        $this->assertFalse($criterion->match(array('postalCode' => 'abcd')));
        $this->assertFalse($criterion->match(array('postalCode' => '10101')));
    }
}
