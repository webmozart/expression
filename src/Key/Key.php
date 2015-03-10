<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Key;

use RuntimeException;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\Literal;

/**
 * Checks that an array key matches some expression.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Key extends Literal
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var Expression
     */
    private $expr;

    /**
     * Creates the expression.
     *
     * @param string     $key  The array key.
     * @param Expression $expr The expression.
     */
    public function __construct($key, Expression $expr)
    {
        $this->key = $key;
        $this->expr = $expr;
    }

    /**
     * Returns the array key.
     *
     * @return string The array key.
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the expression.
     *
     * @return Expression The expression.
     */
    public function getExpression()
    {
        return $this->expr;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_array($value)) {
            throw new RuntimeException(sprintf(
                'Cannot evaluate expression: Expected an array. Got: %s',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        if (!array_key_exists($this->key, $value)) {
            return false;
        }

        return $this->expr->evaluate($value[$this->key]);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Expression $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var Key $other */
        return $this->key === $other->key && $this->expr->equals($other->expr);
    }
}
