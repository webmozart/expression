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

/**
 * Checks whether an array key matches an expression.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class Key extends Selector
{
    /**
     * @var string
     */
    private $key;

    /**
     * Creates the expression.
     *
     * @param string|int $key  The array key.
     * @param Expression $expr The expression to evaluate for the key.
     */
    public function __construct($key, Expression $expr)
    {
        parent::__construct($expr);

        $this->key = $key;
    }

    /**
     * Returns the array key.
     *
     * @return string|int The array key.
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    protected function select($value)
    {
        if (!is_array($value)) {
            throw new SelectFailedException('Array expected.');
        }

        if (!array_key_exists($this->key, $value)) {
            throw new SelectFailedException('Key not found expected.');
        }

        return $value[$this->key];
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        if (!parent::equivalentTo($other)) {
            return false;
        }

        /** @var Key $other */
        return $this->key == $other->key;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $exprString = parent::toString();

        // Append "functions" with "."
        if (isset($exprString[0]) && ctype_alpha($exprString[0])) {
            return $this->key.'.'.$exprString;
        }

        return $this->key.$exprString;
    }
}
