<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Traversal;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Selector\Key;
use Webmozart\Expression\Traversal\ExpressionTraverser;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ExpressionTraverserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ExpressionTraverser
     */
    private $traverser;

    protected function setUp()
    {
        $this->traverser = new ExpressionTraverser();
    }

    public function testAddVisitor()
    {
        $visitor1 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor2 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');

        $this->traverser->addVisitor($visitor1);
        $this->traverser->addVisitor($visitor2);
        $this->traverser->addVisitor($visitor1);

        $this->assertSame(array($visitor1, $visitor2, $visitor1), $this->traverser->getVisitors());
    }

    public function testRemoveVisitor()
    {
        $visitor1 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor2 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');

        $this->traverser->addVisitor($visitor1);
        $this->traverser->addVisitor($visitor2);
        $this->traverser->addVisitor($visitor1);
        $this->traverser->removeVisitor($visitor1);

        $this->assertSame(array($visitor2), $this->traverser->getVisitors());
    }

    public function testTraverse()
    {
        $expr = new GreaterThan(10);

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);
        $visitor->expects($this->at(1))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr, $this->traverser->traverse($expr));
    }

    public function testModifyExprInEnterExpression()
    {
        $expr1 = new GreaterThan(10);
        $expr2 = new GreaterThan(5);

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr2);
        $visitor->expects($this->at(1))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testModifyExprInLeaveExpression()
    {
        $expr1 = new GreaterThan(10);
        $expr2 = new GreaterThan(5);

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testRemoveExpr()
    {
        $expr = new GreaterThan(10);

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);
        $visitor->expects($this->at(1))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr))
            ->willReturn(null);

        $this->traverser->addVisitor($visitor);

        $this->assertNull($this->traverser->traverse($expr));
    }

    public function testTraverseMultipleVisitors()
    {
        $expr1 = new GreaterThan(10);
        $expr2 = new GreaterThan(5);

        $visitor1 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor1->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor1->expects($this->at(1))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr2);

        $visitor2 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor2->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr2))
            ->willReturn($expr2);
        $visitor2->expects($this->at(1))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor1);
        $this->traverser->addVisitor($visitor2);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testTraverseSkipsSubsequentVisitorsIfExpressionRemoved()
    {
        $expr = new GreaterThan(10);

        $visitor1 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor1->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);
        $visitor1->expects($this->at(1))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr))
            ->willReturn(null);

        $visitor2 = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor2->expects($this->never())
            ->method('enterExpression');
        $visitor2->expects($this->never())
            ->method('leaveExpression');

        $this->traverser->addVisitor($visitor1);
        $this->traverser->addVisitor($visitor2);

        $this->assertNull($this->traverser->traverse($expr));
    }

    public function testTraverseNot()
    {
        $expr = new Not($gt = new GreaterThan(10));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(3))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr, $this->traverser->traverse($expr));
    }

    public function testModifyNotChildInEnterExpression()
    {
        $expr1 = new Not($gt1 = new GreaterThan(10));
        $expr2 = new Not($gt2 = new GreaterThan(5));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt2))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testModifyNotChildInLeaveExpression()
    {
        $expr1 = new Not($gt1 = new GreaterThan(10));
        $expr2 = new Not($gt2 = new GreaterThan(5));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testRemoveNotChild()
    {
        $expr1 = new Not($gt1 = new GreaterThan(10));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn(null);

        $this->traverser->addVisitor($visitor);

        $this->assertNull($this->traverser->traverse($expr1));
    }

    public function testTraverseKey()
    {
        $expr = new Key('key', $gt = new GreaterThan(10));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(3))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr, $this->traverser->traverse($expr));
    }

    public function testModifyKeyChildInEnterExpression()
    {
        $expr1 = new Key('key', $gt1 = new GreaterThan(10));
        $expr2 = new Key('key', $gt2 = new GreaterThan(5));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt2))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testModifyKeyChildInLeaveExpression()
    {
        $expr1 = new Key('key', $gt1 = new GreaterThan(10));
        $expr2 = new Key('key', $gt2 = new GreaterThan(5));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testRemoveKeyChild()
    {
        $expr1 = new Key('key', $gt1 = new GreaterThan(10));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn(null);

        $this->traverser->addVisitor($visitor);

        $this->assertNull($this->traverser->traverse($expr1));
    }

    public function testTraverseConjunction()
    {
        $expr = new AndX(array(
            $gt = new GreaterThan(10),
            $same = new Same('5'),
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr, $this->traverser->traverse($expr));
    }

    public function testModifyConjunctInEnterExpression()
    {
        $expr1 = new AndX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));
        $expr2 = new AndX(array(
            $gt2 = new GreaterThan(5),
            $same,
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt2))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testModifyConjunctInLeaveExpression()
    {
        $expr1 = new AndX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));
        $expr2 = new AndX(array(
            $gt2 = new GreaterThan(5),
            $same,
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testRemoveConjunct()
    {
        $expr1 = new AndX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));
        $expr2 = new AndX(array($same));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn(null);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testRemoveAllConjuncts()
    {
        $expr1 = new AndX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn(null);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn(null);

        $this->traverser->addVisitor($visitor);

        $this->assertNull($this->traverser->traverse($expr1));
    }

    public function testTraverseDisjunction()
    {
        $expr = new OrX(array(
            $gt = new GreaterThan(10),
            $same = new Same('5'),
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt))
            ->willReturn($gt);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->identicalTo($expr))
            ->willReturn($expr);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr, $this->traverser->traverse($expr));
    }

    public function testModifyDisjunctInEnterExpression()
    {
        $expr1 = new OrX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));
        $expr2 = new OrX(array(
            $gt2 = new GreaterThan(5),
            $same,
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt2))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testModifyDisjunctInLeaveExpression()
    {
        $expr1 = new OrX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));
        $expr2 = new OrX(array(
            $gt2 = new GreaterThan(5),
            $same,
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt2);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testRemoveDisjunct()
    {
        $expr1 = new OrX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));
        $expr2 = new OrX(array($same));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn(null);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(5))
            ->method('leaveExpression')
            ->with($this->equalTo($expr2))
            ->willReturn($expr2);

        $this->traverser->addVisitor($visitor);

        $this->assertSame($expr2, $this->traverser->traverse($expr1));
    }

    public function testRemoveAllDisjuncts()
    {
        $expr1 = new OrX(array(
            $gt1 = new GreaterThan(10),
            $same = new Same('5'),
        ));

        $visitor = $this->getMock('Webmozart\Expression\Traversal\ExpressionVisitor');
        $visitor->expects($this->at(0))
            ->method('enterExpression')
            ->with($this->identicalTo($expr1))
            ->willReturn($expr1);
        $visitor->expects($this->at(1))
            ->method('enterExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn($gt1);
        $visitor->expects($this->at(2))
            ->method('leaveExpression')
            ->with($this->identicalTo($gt1))
            ->willReturn(null);
        $visitor->expects($this->at(3))
            ->method('enterExpression')
            ->with($this->identicalTo($same))
            ->willReturn($same);
        $visitor->expects($this->at(4))
            ->method('leaveExpression')
            ->with($this->identicalTo($same))
            ->willReturn(null);

        $this->traverser->addVisitor($visitor);

        $this->assertNull($this->traverser->traverse($expr1));
    }
}
