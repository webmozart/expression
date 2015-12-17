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
use Webmozart\Expression\Constraint\Contains;
use Webmozart\Expression\Constraint\EndsWith;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Logic\AlwaysFalse;
use Webmozart\Expression\Logic\AlwaysTrue;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Selector\Key;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DisjunctionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $disjunction = new OrX(array(
            $notNull = new Same('10'),
            $greaterThan = new GreaterThan('age', 0),
        ));

        $this->assertSame(array($notNull, $greaterThan), $disjunction->getDisjuncts());
    }

    public function testCreateInlinesDisjunctions()
    {
        $disjunction = new OrX(array(
            $notNull = new Same('10'),
            new OrX(array($greaterThan = new GreaterThan('age', 0))),
        ));

        $this->assertSame(array($notNull, $greaterThan), $disjunction->getDisjuncts());
    }

    public function testOrX()
    {
        $disjunction1 = new OrX(array($notNull = new Same('10')));

        // Expressions are value objects, hence we must not alter the original
        // conjunction
        $disjunction2 = $disjunction1->orX($greaterThan = new GreaterThan('age', 0));

        $this->assertSame(array($notNull), $disjunction1->getDisjuncts());
        $this->assertSame(array($notNull, $greaterThan), $disjunction2->getDisjuncts());
    }

    public function testOrXIgnoresDuplicates()
    {
        $disjunction1 = new OrX(array($notNull = new Same('10')));
        $disjunction2 = $disjunction1->orX(new Same('10'));

        $this->assertSame($disjunction1, $disjunction2);
    }

    public function testOrXInlinesDisjunctions()
    {
        $disjunction1 = new OrX(array($notNull = new Same('10')));
        $disjunction2 = new OrX(array($greaterThan = new GreaterThan('age', 0)));

        // Expressions are value objects, hence we must not alter the original
        // conjunction
        $disjunction3 = $disjunction1->orX($disjunction2);

        $this->assertSame(array($notNull), $disjunction1->getDisjuncts());
        $this->assertSame(array($greaterThan), $disjunction2->getDisjuncts());
        $this->assertSame(array($notNull, $greaterThan), $disjunction3->getDisjuncts());
    }

    public function testOrFalseIgnored()
    {
        $disjunction1 = new OrX(array($notNull = new Same('10')));
        $disjunction2 = $disjunction1->orFalse();

        $this->assertSame($disjunction1, $disjunction2);
    }

    public function testOrXIgnoresFalse()
    {
        $disjunction1 = new OrX(array($notNull = new Same('10')));
        $disjunction2 = $disjunction1->orX(new AlwaysFalse());

        $this->assertSame($disjunction1, $disjunction2);
    }

    public function testOrTrueReturnsTrue()
    {
        $disjunction1 = new OrX(array($notNull = new Same('10')));
        $disjunction2 = $disjunction1->orTrue();

        $this->assertInstanceOf('Webmozart\Expression\Logic\AlwaysTrue', $disjunction2);
    }

    public function testOrXReturnsTrue()
    {
        $disjunction1 = new OrX(array($notNull = new Same('10')));
        $disjunction2 = $disjunction1->orX($true = new AlwaysTrue());

        $this->assertSame($true, $disjunction2);
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testOr($method, $args, $expected)
    {
        // tested separately
        if ('true' === $method || 'false' === $method) {
            return;
        }

        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'or'.ucfirst($method);
        $disjunction1 = new OrX();

        $disjunction2 = call_user_func_array(array($disjunction1, $method), $args);

        $this->assertEquals(array(), $disjunction1->getDisjuncts());
        $this->assertEquals(array($expected), $disjunction2->getDisjuncts());
    }

    public function testEvaluate()
    {
        $disjunction = new OrX(array(
            new Key('name', new Same('Thomas')),
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($disjunction->evaluate(array('name' => 'Thomas', 'age' => 35)));
        $this->assertTrue($disjunction->evaluate(array('name' => null, 'age' => 35)));
        $this->assertTrue($disjunction->evaluate(array('name' => 'Thomas', 'age' => 0)));
        $this->assertFalse($disjunction->evaluate(array('name' => null, 'age' => 0)));
    }

    public function testEquivalentTo()
    {
        $disjunction1 = new OrX(array(
            new Key('name', new Same('10')),
            new Key('age', new GreaterThan(0)),
        ));

        // disjunctions match independent of the order of the conjuncts
        $disjunction2 = new OrX(array(
            new Key('age', new GreaterThan(0)),
            new Key('name', new Same('10')),
        ));

        $disjunction3 = new OrX(array(
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($disjunction1->equivalentTo($disjunction2));
        $this->assertTrue($disjunction2->equivalentTo($disjunction1));
        $this->assertFalse($disjunction2->equivalentTo($disjunction3));
        $this->assertFalse($disjunction3->equivalentTo($disjunction2));
        $this->assertFalse($disjunction1->equivalentTo($disjunction3));
        $this->assertFalse($disjunction3->equivalentTo($disjunction1));
    }

    public function testToString()
    {
        $expr1 = new OrX();
        $expr2 = new OrX(array(new GreaterThan(10), new EndsWith('.css')));
        $expr3 = new OrX(array(new GreaterThan(10), new AndX(array(new Contains('foo'), new EndsWith('.css')))));

        $this->assertSame('', $expr1->toString());
        $this->assertSame('>10 || endsWith(".css")', $expr2->toString());
        $this->assertSame('>10 || (contains("foo") && endsWith(".css"))', $expr3->toString());
    }
}
