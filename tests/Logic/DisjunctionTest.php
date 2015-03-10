<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Logic;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Comparison\EndsWith;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\Same;
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
            $notNull = new Same('10'),
            $greaterThan = new GreaterThan('age', 0)
        ));

        $this->assertSame(array($notNull, $greaterThan), $disjunction->getDisjuncts());
    }

    public function testCreateInlinesDisjunctions()
    {
        $disjunction = new Disjunction(array(
            $notNull = new Same('10'),
            new Disjunction(array($greaterThan = new GreaterThan('age', 0))),
        ));

        $this->assertSame(array($notNull, $greaterThan), $disjunction->getDisjuncts());
    }

    public function testOrX()
    {
        $disjunction1 = new Disjunction(array($notNull = new Same('10')));

        // Expressions are value objects, hence we must not alter the original
        // conjunction
        $disjunction2 = $disjunction1->orX($greaterThan = new GreaterThan('age', 0));

        $this->assertSame(array($notNull), $disjunction1->getDisjuncts());
        $this->assertSame(array($notNull, $greaterThan), $disjunction2->getDisjuncts());
    }

    public function testOrXIgnoresDuplicates()
    {
        $disjunction1 = new Disjunction(array($notNull = new Same('10')));
        $disjunction2 = $disjunction1->orX(new Same('10'));

        $this->assertSame($disjunction1, $disjunction2);
    }

    public function testOrXInlinesDisjunctions()
    {
        $disjunction1 = new Disjunction(array($notNull = new Same('10')));
        $disjunction2 = new Disjunction(array($greaterThan = new GreaterThan('age', 0)));

        // Expressions are value objects, hence we must not alter the original
        // conjunction
        $disjunction3 = $disjunction1->orX($disjunction2);

        $this->assertSame(array($notNull), $disjunction1->getDisjuncts());
        $this->assertSame(array($greaterThan), $disjunction2->getDisjuncts());
        $this->assertSame(array($notNull, $greaterThan), $disjunction3->getDisjuncts());
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
        $disjunction1 = new Disjunction();

        $disjunction2 = call_user_func_array(array($disjunction1, $method), $args);

        $this->assertEquals(array(), $disjunction1->getDisjuncts());
        $this->assertEquals(array($expected), $disjunction2->getDisjuncts());
    }

    public function testMatch()
    {
        $disjunction = new Disjunction(array(
            new Key('name', new Same('Thomas')),
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
            new Key('name', new Same('10')),
            new Key('age', new GreaterThan(0)),
        ));

        // disjunctions match independent of the order of the conjuncts
        $disjunction2 = new Disjunction(array(
            new Key('age', new GreaterThan(0)),
            new Key('name', new Same('10')),
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

    public function testToString()
    {
        $expr1 = new Disjunction();
        $expr2 = new Disjunction(array(new GreaterThan(10), new EndsWith('.css')));

        $this->assertSame('()', $expr1->toString());
        $this->assertSame('(>10 || endsWith(".css"))', $expr2->toString());
    }
}
