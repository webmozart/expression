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
use Webmozart\Criteria\Atom\IsEmpty;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IsEmptyTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new IsEmpty('amount');

        $this->assertTrue($criterion->match(array('amount' => null)));
        $this->assertTrue($criterion->match(array('amount' => 0)));
        $this->assertTrue($criterion->match(array('amount' => '')));
        $this->assertTrue($criterion->match(array('amount' => false)));
        $this->assertFalse($criterion->match(array('amount' => true)));
        $this->assertFalse($criterion->match(array('amount' => 'abcd')));
    }
}
