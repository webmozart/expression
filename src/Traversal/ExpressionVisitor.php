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

/**
 * Visits the nodes of an {@link Expression} tree.
 *
 * The visitor needs to be attached to a {@link ExpressionTraverser}. The
 * traverser invokes the visitor for every node of the expression tree.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface ExpressionVisitor
{
    /**
     * Called when the traverser enters an expression.
     *
     * @param Expression $expr The expression.
     *
     * @return Expression The modified expression.
     */
    public function enterExpression(Expression $expr);

    /**
     * Called when the traverser leaves an expression.
     *
     * @param Expression $expr The expression.
     *
     * @return Expression|null The modified expression or `null` if the
     *                         expression should be removed from the tree.
     */
    public function leaveExpression(Expression $expr);
}
