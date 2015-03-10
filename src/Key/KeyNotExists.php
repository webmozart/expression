<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Key;

use RuntimeException;
use Webmozart\Criteria\Logic\Literal;

/**
 * Checks that an array key does not exist.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KeyNotExists extends Literal
{
    /**
     * @var string
     */
    private $key;

    /**
     * Creates the criterion.
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
    public function match($value)
    {
        if (!is_array($value)) {
            throw new RuntimeException(sprintf(
                'Cannot evaluate criterion: Expected an array. Got: %s',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return !array_key_exists($this->key, $value);
    }
}
