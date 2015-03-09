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
use Webmozart\Criteria\Formula\Disjunction;
use Webmozart\Criteria\Literal\Not;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DisjunctionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $conjunction = new Disjunction(array(
            $notNull = new NotNull('name'),
            $greaterThan = new GreaterThan('age', 0)
        ));

        $this->assertSame(array($notNull, $greaterThan), $conjunction->getDisjuncts());
    }

    public function testOrX()
    {
        $conjunction = new Disjunction();
        $conjunction->orX($notNull = new NotNull('name'));
        $conjunction->orX($greaterThan = new GreaterThan('age', 0));

        $this->assertSame(array($notNull, $greaterThan), $conjunction->getDisjuncts());
    }

    public function testOrXIgnoresDuplicates()
    {
        $conjunction = new Disjunction();
        $conjunction->orX($notNull = new NotNull('name'));
        $conjunction->orX(new NotNull('name'));

        $this->assertSame(array($notNull), $conjunction->getDisjuncts());
    }

    /**
     * @dataProvider \Webmozart\Criteria\Tests\CriterionTest::getCriterionTests
     */
    public function testOr($method, $args, $expected)
    {
        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'or'.ucfirst($method);
        $conjunction = new Disjunction();

        call_user_func_array(array($conjunction, $method), $args);

        $this->assertEquals(array($expected), $conjunction->getDisjuncts());
    }

    public function testMatch()
    {
        $conjunction = new Disjunction(array(
            new NotNull('name'),
            new GreaterThan('age', 0),
        ));

        $this->assertTrue($conjunction->match(array('name' => 'Thomas', 'age' => 35)));
        $this->assertTrue($conjunction->match(array('name' => null, 'age' => 35)));
        $this->assertTrue($conjunction->match(array('name' => 'Thomas', 'age' => 0)));
        $this->assertFalse($conjunction->match(array('name' => null, 'age' => 0)));
    }

    public function testEquals()
    {
        $conjunction1 = new Disjunction(array(
            new NotNull('name'),
            new Not(new GreaterThan('age', 0)),
        ));

        // conjunctions match independent of the order of the conjuncts
        $conjunction2 = new Disjunction(array(
            new Not(new GreaterThan('age', 0)),
            new NotNull('name'),
        ));

        $conjunction3 = new Disjunction(array(
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
