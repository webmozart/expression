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
use Webmozart\Criteria\Criteria;
use Webmozart\Criteria\Logic\Literal;

/**
 * Checks that an array key matches some criteria.
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
     * @var Criteria
     */
    private $criteria;

    /**
     * Creates the criterion.
     *
     * @param string   $key      The array key.
     * @param Criteria $criteria The criteria.
     */
    public function __construct($key, Criteria $criteria)
    {
        $this->key = $key;
        $this->criteria = $criteria;
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
     * Returns the criteria.
     *
     * @return Criteria The criteria.
     */
    public function getCriteria()
    {
        return $this->criteria;
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

        if (!array_key_exists($this->key, $value)) {
            return false;
        }

        return $this->criteria->match($value[$this->key]);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Criteria $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var Key $other */
        return $this->key === $other->key && $this->criteria->equals($other->criteria);
    }
}
