<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Formula;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\GreaterThan;
use Webmozart\Criteria\Atom\NotNull;
use Webmozart\Criteria\Formula\Conjunction;
use Webmozart\Criteria\Literal\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ConjunctionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $conjunction = new Conjunction(array(
            $notNull = new NotNull('name'),
            $greaterThan = new GreaterThan('age', 0)
        ));

        $this->assertSame(array($notNull, $greaterThan), $conjunction->getConjuncts());
    }

    public function testAndX()
    {
        $conjunction = new Conjunction();
        $conjunction->andX($notNull = new NotNull('name'));
        $conjunction->andX($greaterThan = new GreaterThan('age', 0));

        $this->assertSame(array($notNull, $greaterThan), $conjunction->getConjuncts());
    }

    /**
     * @dataProvider \Webmozart\Criteria\Tests\CriterionTest::getCriterionTests
     */
    public function testAnd($method, $args, $expected)
    {
        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'and'.ucfirst($method);
        $conjunction = new Conjunction();

        call_user_func_array(array($conjunction, $method), $args);

        $this->assertEquals(array($expected), $conjunction->getConjuncts());
    }

    public function testMatch()
    {
        $conjunction = new Conjunction(array(
            new NotNull('name'),
            new GreaterThan('age', 0),
        ));

        $this->assertTrue($conjunction->match(array('name' => 'Thomas', 'age' => 35)));
        $this->assertFalse($conjunction->match(array('name' => null, 'age' => 35)));
        $this->assertFalse($conjunction->match(array('name' => 'Thomas', 'age' => 0)));
        $this->assertFalse($conjunction->match(array('name' => null, 'age' => 0)));
    }

    public function testEquals()
    {
        $conjunction1 = new Conjunction(array(
            new NotNull('name'),
            new Not(new GreaterThan('age', 0)),
        ));

        // conjunctions match independent of the order of the conjuncts
        $conjunction2 = new Conjunction(array(
            new Not(new GreaterThan('age', 0)),
            new NotNull('name'),
        ));

        $conjunction3 = new Conjunction(array(
            new Not(new GreaterThan('age', 0)),
        ));

        $this->assertTrue($conjunction1->equals($conjunction2));
        $this->assertTrue($conjunction2->equals($conjunction1));
        $this->assertFalse($conjunction2->equals($conjunction3));
        $this->assertFalse($conjunction3->equals($conjunction2));
        $this->assertFalse($conjunction1->equals($conjunction3));
        $this->assertFalse($conjunction3->equals($conjunction1));
    }
}
