<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Key;

use RuntimeException;
use Webmozart\Expression\Logic\Literal;
use Webmozart\Expression\Util\StringUtil;

/**
 * Checks that an array key exists.
 *
 * @since  1.0
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
        $this->key = $key;
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
            throw new RuntimeException(sprintf(
                'Cannot evaluate expression: Expected an array. Got: %s',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return array_key_exists($this->key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'keyExists('.StringUtil::formatValue($this->key).')';
    }
}
