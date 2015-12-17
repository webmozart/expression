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
use Webmozart\Expression\Expr;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Selector\Key;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ConjunctionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $conjunction = new AndX(array(
            $notNull = new Same('10'),
            $greaterThan = new GreaterThan('age', 0),
        ));

        $this->assertSame(array($notNull, $greaterThan), $conjunction->getConjuncts());
    }

    public function testCreateInlinesConjunction()
    {
        $conjunction = new AndX(array(
            $notNull = new Same('10'),
            new AndX(array($greaterThan = new GreaterThan('age', 0))),
        ));

        $this->assertSame(array($notNull, $greaterThan), $conjunction->getConjuncts());
    }

    public function testAndX()
    {
        $conjunction1 = new AndX(array($notNull = new Same('10')));

        // Expressions are value objects, hence we must not alter the original
        // conjunction
        $conjunction2 = $conjunction1->andX($greaterThan = new GreaterThan('age', 0));

        $this->assertSame(array($notNull), $conjunction1->getConjuncts());
        $this->assertSame(array($notNull, $greaterThan), $conjunction2->getConjuncts());
    }

    public function testAndXIgnoresDuplicates()
    {
        $conjunction1 = new AndX(array($notNull = new Same('10')));
        $conjunction2 = $conjunction1->andX(new Same('10'));

        $this->assertSame($conjunction1, $conjunction2);
    }

    public function testAndXInlinesConjunctions()
    {
        $conjunction1 = new AndX(array($notNull = new Same('10')));
        $conjunction2 = new AndX(array($greaterThan = new GreaterThan('name')));

        $conjunction3 = $conjunction1->andX($conjunction2);

        $this->assertSame(array($notNull), $conjunction1->getConjuncts());
        $this->assertSame(array($greaterThan), $conjunction2->getConjuncts());
        $this->assertSame(array($notNull, $greaterThan), $conjunction3->getConjuncts());
    }

    public function testAndTrueIgnored()
    {
        $conjunction1 = new AndX(array($notNull = new Same('10')));
        $conjunction2 = $conjunction1->andTrue();

        $this->assertSame($conjunction1, $conjunction2);
    }

    public function testAndXIgnoresTrue()
    {
        $conjunction1 = new AndX(array($notNull = new Same('10')));
        $conjunction2 = $conjunction1->andX(Expr::true());

        $this->assertSame($conjunction1, $conjunction2);
    }

    public function testAndFalseReturnsFalse()
    {
        $conjunction1 = new AndX(array($notNull = new Same('10')));
        $conjunction2 = $conjunction1->andFalse();

        $this->assertInstanceOf('Webmozart\Expression\Logic\AlwaysFalse', $conjunction2);
    }

    public function testAndXReturnsFalse()
    {
        $conjunction1 = new AndX(array($notNull = new Same('10')));
        $conjunction2 = $conjunction1->andX($false = Expr::false());

        $this->assertSame($false, $conjunction2);
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getMethodTests
     */
    public function testAnd($method, $args, $expected)
    {
        // tested separately
        if ('true' === $method || 'false' === $method) {
            return;
        }

        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'and'.ucfirst($method);
        $conjunction1 = new AndX();

        $conjunction2 = call_user_func_array(array($conjunction1, $method), $args);

        $this->assertEquals(array(), $conjunction1->getConjuncts());
        $this->assertEquals(array($expected), $conjunction2->getConjuncts());
    }

    public function testEvaluate()
    {
        $conjunction = new AndX(array(
            new Key('name', new Same('Thomas')),
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($conjunction->evaluate(array('name' => 'Thomas', 'age' => 35)));
        $this->assertFalse($conjunction->evaluate(array('name' => null, 'age' => 35)));
        $this->assertFalse($conjunction->evaluate(array('name' => 'Thomas', 'age' => 0)));
        $this->assertFalse($conjunction->evaluate(array('name' => null, 'age' => 0)));
    }

    public function testEquivalentTo()
    {
        $conjunction1 = new AndX(array(
            new Key('name', new Same('10')),
            new Key('age', new GreaterThan(0)),
        ));

        // conjunctions match independent of the order of the conjuncts
        $conjunction2 = new AndX(array(
            new Key('age', new GreaterThan(0)),
            new Key('name', new Same('10')),
        ));

        $conjunction3 = new AndX(array(
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($conjunction1->equivalentTo($conjunction2));
        $this->assertTrue($conjunction2->equivalentTo($conjunction1));
        $this->assertFalse($conjunction2->equivalentTo($conjunction3));
        $this->assertFalse($conjunction3->equivalentTo($conjunction2));
        $this->assertFalse($conjunction1->equivalentTo($conjunction3));
        $this->assertFalse($conjunction3->equivalentTo($conjunction1));
    }

    public function testToString()
    {
        $expr1 = new AndX();
        $expr2 = new AndX(array(new GreaterThan(10), new EndsWith('.css')));
        $expr3 = new AndX(array(new GreaterThan(10), new OrX(array(new Contains('foo'), new EndsWith('.css')))));

        $this->assertSame('', $expr1->toString());
        $this->assertSame('>10 && endsWith(".css")', $expr2->toString());
        $this->assertSame('>10 && (contains("foo") || endsWith(".css"))', $expr3->toString());
    }
}
