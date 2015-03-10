<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Logic;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Comparison\GreaterThan;
use Webmozart\Criteria\Comparison\LessThan;
use Webmozart\Criteria\Comparison\StartsWith;
use Webmozart\Criteria\Logic\Disjunction;
use Webmozart\Criteria\Logic\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Not(new StartsWith('Thomas'));

        $this->assertTrue($criterion->match('Mr. Thomas Edison'));
        $this->assertFalse($criterion->match('Thomas Edison'));
    }

    public function testEquals()
    {
        $criterion1 = new Not(new Disjunction(array(new LessThan(0), new GreaterThan(10))));
        $criterion2 = new Not(new Disjunction(array(new GreaterThan(10), new LessThan(0))));
        $criterion3 = new Not(new Disjunction(array(new GreaterThan(10))));

        $this->assertTrue($criterion1->equals($criterion2));
        $this->assertFalse($criterion2->equals($criterion3));
        $this->assertFalse($criterion1->equals($criterion3));
    }
}
