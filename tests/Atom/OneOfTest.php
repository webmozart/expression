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
use Webmozart\Criteria\Atom\Equals;
use Webmozart\Criteria\Atom\OneOf;
use Webmozart\Criteria\Atom\Same;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OneOfTest extends PHPUnit_Framework_TestCase
{
    public function testMatchStrict()
    {
        $criterion = new OneOf('amount', array('1', '2', '3'));

        $this->assertTrue($criterion->match(array('amount' => '1')));
        $this->assertFalse($criterion->match(array('amount' => 1)));
        $this->assertFalse($criterion->match(array('amount' => 1.0)));
        $this->assertFalse($criterion->match(array('amount' => 0)));
        $this->assertFalse($criterion->match(array('amount' => 10)));
        $this->assertFalse($criterion->match(array('amount' => null)));
    }

    public function testMatchNonStrict()
    {
        $criterion = new OneOf('amount', array('1', '2', '3'), false);

        $this->assertTrue($criterion->match(array('amount' => '1')));
        $this->assertTrue($criterion->match(array('amount' => 1)));
        $this->assertTrue($criterion->match(array('amount' => 1.0)));
        $this->assertFalse($criterion->match(array('amount' => 0)));
        $this->assertFalse($criterion->match(array('amount' => 10)));
        $this->assertFalse($criterion->match(array('amount' => null)));
    }
}
