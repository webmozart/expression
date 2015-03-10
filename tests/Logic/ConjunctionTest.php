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
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\NotNull;
use Webmozart\Expression\Key\Key;
use Webmozart\Expression\Logic\Conjunction;

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
        $conjunction1 = new Conjunction(array($notNull = new NotNull('name')));

        // Expressions are value objects, hence we must not alter the original
        // conjunction
        $conjunction2 = $conjunction1->andX($greaterThan = new GreaterThan('age', 0));

        $this->assertSame(array($notNull), $conjunction1->getConjuncts());
        $this->assertSame(array($notNull, $greaterThan), $conjunction2->getConjuncts());
    }

    public function testAndXIgnoresDuplicates()
    {
        $conjunction1 = new Conjunction(array($notNull = new NotNull('name')));
        $conjunction2 = $conjunction1->andX(new NotNull('name'));

        $this->assertSame($conjunction1, $conjunction2);
    }

    /**
     * @dataProvider \Webmozart\Expression\Tests\ExprTest::getCriterionTests
     */
    public function testAnd($method, $args, $expected)
    {
        if ('is' === substr($method, 0, 2)) {
            $method = substr($method, 2);
        }

        $method = 'and'.ucfirst($method);
        $conjunction1 = new Conjunction();

        $conjunction2 = call_user_func_array(array($conjunction1, $method), $args);

        $this->assertEquals(array(), $conjunction1->getConjuncts());
        $this->assertEquals(array($expected), $conjunction2->getConjuncts());
    }

    public function testMatch()
    {
        $conjunction = new Conjunction(array(
            new Key('name', new NotNull()),
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($conjunction->evaluate(array('name' => 'Thomas', 'age' => 35)));
        $this->assertFalse($conjunction->evaluate(array('name' => null, 'age' => 35)));
        $this->assertFalse($conjunction->evaluate(array('name' => 'Thomas', 'age' => 0)));
        $this->assertFalse($conjunction->evaluate(array('name' => null, 'age' => 0)));
    }

    public function testEquals()
    {
        $conjunction1 = new Conjunction(array(
            new Key('name', new NotNull()),
            new Key('age', new GreaterThan(0)),
        ));

        // conjunctions match independent of the order of the conjuncts
        $conjunction2 = new Conjunction(array(
            new Key('age', new GreaterThan(0)),
            new Key('name', new NotNull()),
        ));

        $conjunction3 = new Conjunction(array(
            new Key('age', new GreaterThan(0)),
        ));

        $this->assertTrue($conjunction1->equals($conjunction2));
        $this->assertTrue($conjunction2->equals($conjunction1));
        $this->assertFalse($conjunction2->equals($conjunction3));
        $this->assertFalse($conjunction3->equals($conjunction2));
        $this->assertFalse($conjunction1->equals($conjunction3));
        $this->assertFalse($conjunction3->equals($conjunction1));
    }
}
