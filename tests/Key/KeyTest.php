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
use Webmozart\Criteria\Comparison\LessThan;
use Webmozart\Criteria\Key\Key;
use Webmozart\Criteria\Logic\Disjunction;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Key('key', new GreaterThan(10));

        $this->assertTrue($criterion->match(array('key' => 11)));
        $this->assertFalse($criterion->match(array('key' => 9)));
    }

    public function testMatchReturnsFalseIfKeyNotFound()
    {
        $criterion = new Key('key', new GreaterThan(10));

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

    public function testEquals()
    {
        $criterion1 = new Key('key', new Disjunction(array(new LessThan(0), new GreaterThan(10))));
        $criterion2 = new Key('key', new Disjunction(array(new GreaterThan(10), new LessThan(0))));
        $criterion3 = new Key('other', new Disjunction(array(new LessThan(0), new GreaterThan(10))));
        $criterion4 = new Key('key', new Disjunction(array(new LessThan(0))));

        $this->assertTrue($criterion1->equals($criterion2));
        $this->assertTrue($criterion2->equals($criterion1));

        $this->assertFalse($criterion1->equals($criterion3));
        $this->assertFalse($criterion3->equals($criterion1));

        $this->assertFalse($criterion1->equals($criterion4));
        $this->assertFalse($criterion4->equals($criterion1));

        $this->assertFalse($criterion2->equals($criterion3));
        $this->assertFalse($criterion3->equals($criterion2));

        $this->assertFalse($criterion2->equals($criterion4));
        $this->assertFalse($criterion4->equals($criterion2));

        $this->assertFalse($criterion3->equals($criterion4));
        $this->assertFalse($criterion4->equals($criterion3));
    }
}
