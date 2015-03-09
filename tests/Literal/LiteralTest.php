<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Literal;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\NotNull;
use Webmozart\Criteria\Atom\Null;
use Webmozart\Criteria\Formula\Conjunction;
use Webmozart\Criteria\Formula\Disjunction;
use Webmozart\Criteria\Tests\Literal\Fixtures\TestLiteral;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LiteralTest extends PHPUnit_Framework_TestCase
{
    public function testAndX()
    {
        $literal = new TestLiteral();
        $criterion = new Null('field');

        $this->assertEquals(new Conjunction(array($literal, $criterion)), $literal->andX($criterion));
    }

    public function testAndXIgnoresDuplicates()
    {
        $literal = new TestLiteral('field');

        $this->assertEquals($literal, $literal->andX(new TestLiteral('field')));
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
        $literal = new TestLiteral();

        $result = call_user_func_array(array($literal, $method), $args);

        $this->assertEquals(new Conjunction(array($literal, $expected)), $result);
    }

    public function testOrX()
    {
        $literal = new TestLiteral();
        $criterion = new Null('field');

        $this->assertEquals(new Disjunction(array($literal, $criterion)), $literal->orX($criterion));
    }

    public function testOrXIgnoresDuplicates()
    {
        $literal = new TestLiteral('field');

        $this->assertEquals($literal, $literal->orX(new TestLiteral('field')));
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
        $literal = new TestLiteral();

        $result = call_user_func_array(array($literal, $method), $args);

        $this->assertEquals(new Disjunction(array($literal, $expected)), $result);
    }

    public function testEquals()
    {
        $criterion1 = new TestLiteral('foo');
        $criterion2 = new Null('field');

        $this->assertTrue($criterion1->equals(new TestLiteral('foo')));
        $this->assertFalse($criterion1->equals(new TestLiteral('bar')));
        $this->assertTrue($criterion2->equals(new Null('field')));
        $this->assertFalse($criterion2->equals(new NotNull('field')));
    }
}
