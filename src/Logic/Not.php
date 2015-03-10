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
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Not extends Literal
{
    /**
     * @var Expression
     */
    private $negatedExpression;

    /**
     * Creates the negation.
     *
     * @param Expression $expr The negated expression.
     */
    public function __construct(Expression $expr)
    {
        $this->negatedExpression = $expr;
    }

    /**
     * Returns the negated expression.
     *
     * @return Expression The negated expression.
     */
    public function getNegatedExpression()
    {
        return $this->negatedExpression;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return !$this->negatedExpression->evaluate($value);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Expression $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var Not $other */
        return $this->negatedExpression->equals($other->negatedExpression);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
    }
}
