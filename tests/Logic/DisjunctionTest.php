<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Logic;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\NotNull;
use Webmozart\Expression\Key\Key;
use Webmozart\Expression\Logic\Disjunction;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DisjunctionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $disjunction = new Disjunction(array(
            $notNull = new NotNull('name'),
            $greaterThan = new GreaterThan('age', 0)
        ));

        $this->assertSame(array($notNull, $greaterThan), $disjunction->getDisjuncts());
    }

    public function testOrX()
    {
        $disjunction = new Disjunction();
        $disjunction->orX($notNull = new NotNull('name'));
        $disjunction->orX($greaterThan = new GreaterThan('age', 0));

        $this->assertSame(array($notNull, $greaterThan), $disjunction->getDisjuncts());
    }

    public function testOrXIgnoresDuplicates()
    {
        $disjunction = new Disjunction();
        $disjunction->orX($notNull = new NotNull('name'));
        $disjunction->orX(new NotNull('name'));

        $this->assertSame(array($notNull), $disjunction->getDisjuncts());
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getCriterionTests
     */
    public function testOr($method, $args, $expected)
    {
        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'or'.ucfirst($method);
        $disjunction = new Disjunction();

        call_user_func_array(array($disjunction, $method), $args);

        $this->assertEquals(array($expected), $disjunction->getDisjuncts());
    }

    public function testMatch()
    {
        $disjunction = new Disjunction(array(
            new Key('name', new NotNull()),
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($disjunction->evaluate(array('name' => 'Thomas', 'age' => 35)));
        $this->assertTrue($disjunction->evaluate(array('name' => null, 'age' => 35)));
        $this->assertTrue($disjunction->evaluate(array('name' => 'Thomas', 'age' => 0)));
        $this->assertFalse($disjunction->evaluate(array('name' => null, 'age' => 0)));
    }

    public function testEquals()
    {
        $disjunction1 = new Disjunction(array(
            new Key('name', new NotNull()),
            new Key('age', new GreaterThan(0)),
        ));

        // disjunctions match independent of the order of the conjuncts
        $disjunction2 = new Disjunction(array(
            new Key('age', new GreaterThan(0)),
            new Key('name', new NotNull()),
        ));

        $disjunction3 = new Disjunction(array(
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($disjunction1->equals($disjunction2));
        $this->assertTrue($disjunction2->equals($disjunction1));
        $this->assertFalse($disjunction2->equals($disjunction3));
        $this->assertFalse($disjunction3->equals($disjunction2));
        $this->assertFalse($disjunction1->equals($disjunction3));
        $this->assertFalse($disjunction3->equals($disjunction1));
    }
}
