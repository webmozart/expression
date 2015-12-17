<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Constraint;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\Literal;
use Webmozart\Expression\Util\StringUtil;

/**
 * Checks that an array key exists.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyExists extends Literal
{
    /**
     * @var string
     */
    private $key;

    /**
     * Creates the expression.
     *
     * @param string $key The array key.
     */
    public function __construct($key)
    {
        $this->key = (string) $key;
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
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_array($value)) {
            return false;
        }

        return array_key_exists($this->key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        // Since this class is final, we can check with instanceof
        return $other instanceof $this && $this->key === $other->key;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'keyExists('.StringUtil::formatValue($this->key).')';
    }
}
