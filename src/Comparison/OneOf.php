<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Comparison;

use Webmozart\Criteria\Criteria;
use Webmozart\Criteria\Logic\Literal;

/**
 * Checks that a value is one of a list of values.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OneOf extends Literal
{
    /**
     * @var array
     */
    private $acceptedValues;

    /**
     * @var bool
     */
    private $strict;

    /**
     * Creates the criterion.
     *
     * @param array $acceptedValues The accepted value.
     * @param bool  $strict         Whether to do strict comparison.
     */
    public function __construct(array $acceptedValues, $strict = true)
    {
        $this->acceptedValues = $acceptedValues;
        $this->strict = $strict;
    }

    /**
     * Returns the accepted values.
     *
     * @return array The accepted values.
     */
    public function getAcceptedValues()
    {
        return $this->acceptedValues;
    }

    /**
     * Returns whether the value is compared strictly.
     *
     * @return boolean Returns `true` if using strict comparison (`===`) and
     *                 `false` if using weak comparison (`==`).
     */
    public function isStrict()
    {
        return $this->strict;
    }

    /**
     * {@inheritdoc}
     */
    public function match($value)
    {
        return in_array($value, $this->acceptedValues, $this->strict);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Criteria $other)
    {
        if (1 === count($this->acceptedValues)) {
            // OneOf is logically equivalent to Same if strict and only one value
            if ($this->strict && $other instanceof Same) {
                return reset($this->acceptedValues) === $other->getComparedValue();
            }

            // OneOf is logically equivalent to Equals if not strict and only one value
            if (!$this->strict && $other instanceof Equals) {
                return reset($this->acceptedValues) == $other->getComparedValue();
            }
        }

        return $this == $other;
    }
}
