<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Logic;

use Webmozart\Expression\Expression;

/**
 * Negates another expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Not extends Literal
{
    /**
     * @var Expression
     */
    private $expr;

    /**
     * Creates the negation.
     *
     * @param Expression $expr The negated expression.
     */
    public function __construct(Expression $expr)
    {
        $this->expr = $expr;
    }

    /**
     * Returns the negated expression.
     *
     * @return Expression The negated expression.
     */
    public function getNegatedExpression()
    {
        return $this->expr;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return !$this->expr->evaluate($value);
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        // Since this class is final, we can check with instanceof
        return $other instanceof $this && $this->expr->equivalentTo($other->expr);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $exprString = $this->expr->toString();

        if (isset($exprString[0]) && '(' === $exprString[0]) {
            return 'not'.$exprString;
        }

        return 'not('.$exprString.')';
    }
}
