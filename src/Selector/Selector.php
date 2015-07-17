<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Selector;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\Literal;

/**
 * A logical selector.
 *
 * Evaluates an expression for elements of a structured value.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Selector extends Literal
{
    /**
     * @var Expression
     */
    protected $expr;

    /**
     * Checks whether a value selected from the evaluated value matches an
     * expression.
     *
     * @param Expression $expr The expression to evaluate for the selected value.
     */
    public function __construct(Expression $expr)
    {
        $this->expr = $expr;
    }

    /**
     * Returns the expression that is evaluated for the selected value.
     *
     * @return Expression The inner expression.
     */
    public function getExpression()
    {
        return $this->expr;
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /* @var Selector $other */
        return $this->expr->equivalentTo($other->expr);
    }
}
