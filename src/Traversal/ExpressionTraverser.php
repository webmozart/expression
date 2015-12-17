<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Traversal;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Selector\Key;

/**
 * Traverses {@link Expression} instances.
 *
 * You can attach {@link ExpressionVisitor} instances to the traverse which
 * will be invoked for every node of the expression tree.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ExpressionTraverser
{
    /**
     * @var ExpressionVisitor[]
     */
    private $visitors = array();

    /**
     * Adds a visitor to the traverser.
     *
     * The visitors are invoked in the order in which they are added.
     *
     * @param ExpressionVisitor $visitor The visitor to add.
     */
    public function addVisitor(ExpressionVisitor $visitor)
    {
        $this->visitors[] = $visitor;
    }

    /**
     * Removes a visitor from the traverser.
     *
     * If the visitor was added multiple times, all instances are removed.
     *
     * @param ExpressionVisitor $visitor The visitor to remove.
     */
    public function removeVisitor(ExpressionVisitor $visitor)
    {
        while (false !== ($key = array_search($visitor, $this->visitors, true))) {
            unset($this->visitors[$key]);
        }

        $this->visitors = array_values($this->visitors);
    }

    /**
     * Returns the visitors of the traverser.
     *
     * @return ExpressionVisitor[] The visitors.
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * Traverses an expression.
     *
     * @param Expression $expr The expression to traverse.
     *
     * @return Expression The modified expression. May be `null` if the
     *                    expression was removed entirely.
     */
    public function traverse(Expression $expr)
    {
        // Do one full traversal per visitor. If any of the visitors removes
        // the expression entirely, subsequent visitors are not invoked.
        foreach ($this->visitors as $visitor) {
            $expr = $this->traverseForVisitor($expr, $visitor);

            if (!$expr) {
                return null;
            }
        }

        return $expr;
    }

    private function traverseForVisitor(Expression $expr, ExpressionVisitor $visitor)
    {
        $expr = $visitor->enterExpression($expr);

        if ($expr instanceof Key) {
            $expr = $this->traverseKey($expr);
        } elseif ($expr instanceof Not) {
            $expr = $this->traverseNot($expr);
        } elseif ($expr instanceof AndX) {
            $expr = $this->traverseConjunction($expr);
        } elseif ($expr instanceof OrX) {
            $expr = $this->traverseDisjunction($expr);
        }

        if ($expr) {
            $expr = $visitor->leaveExpression($expr);
        }

        return $expr;
    }

    private function traverseKey(Key $expr)
    {
        $innerExpr1 = $expr->getExpression();
        $innerExpr2 = $this->traverse($innerExpr1);

        if ($innerExpr1 === $innerExpr2) {
            return $expr;
        }

        return $innerExpr2 ? new Key($expr->getKey(), $innerExpr2) : null;
    }

    private function traverseNot(Not $expr)
    {
        $negatedExpr1 = $expr->getNegatedExpression();
        $negatedExpr2 = $this->traverse($negatedExpr1);

        if ($negatedExpr1 === $negatedExpr2) {
            return $expr;
        }

        return $negatedExpr2 ? new Not($negatedExpr2) : null;
    }

    private function traverseConjunction(AndX $expr)
    {
        $conjuncts1 = $expr->getConjuncts();
        $conjuncts2 = array();

        foreach ($conjuncts1 as $conjunct) {
            if ($conjunct = $this->traverse($conjunct)) {
                $conjuncts2[] = $conjunct;
            }
        }

        if ($conjuncts1 === $conjuncts2) {
            return $expr;
        }

        return $conjuncts2 ? new AndX($conjuncts2) : null;
    }

    private function traverseDisjunction(OrX $expr)
    {
        $disjuncts1 = $expr->getDisjuncts();
        $disjuncts2 = array();

        foreach ($disjuncts1 as $disjunct) {
            if ($disjunct = $this->traverse($disjunct)) {
                $disjuncts2[] = $disjunct;
            }
        }

        if ($disjuncts1 === $disjuncts2) {
            return $expr;
        }

        return $disjuncts2 ? new OrX($disjuncts2) : null;
    }
}
