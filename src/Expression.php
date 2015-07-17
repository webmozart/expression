<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression;

/**
 * A logical expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface Expression
{
    /**
     * Evaluates the expression with the given value.
     *
     * @param mixed $value A value.
     *
     * @return bool Returns `true` if the value satisfies the expression and
     *              `false` otherwise.
     */
    public function evaluate($value);

    /**
     * Returns whether this expression is logically equivalent to another expression.
     *
     * @param Expression $other Some expression.
     *
     * @return bool Returns `true` if the expressions are logically equivalent
     *              and `false` otherwise.
     */
    public function equivalentTo(Expression $other);

    /**
     * Returns a string representation of the expression.
     *
     * @return string The expression as string.
     */
    public function toString();
}
