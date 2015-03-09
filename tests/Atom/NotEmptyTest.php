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
use Webmozart\Criteria\Atom\NotEmpty;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotEmptyTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new NotEmpty('amount');

        $this->assertTrue($criterion->match(array('amount' => true)));
        $this->assertTrue($criterion->match(array('amount' => 'abcd')));
        $this->assertFalse($criterion->match(array('amount' => null)));
        $this->assertFalse($criterion->match(array('amount' => 0)));
        $this->assertFalse($criterion->match(array('amount' => '')));
        $this->assertFalse($criterion->match(array('amount' => false)));
    }
}
