<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Atom;

use Webmozart\Criteria\Criteria;

/**
 * Checks that a field value is one of a list of values.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OneOf extends Atom
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
     * @param string $field          The field name.
     * @param array  $acceptedValues The accepted value.
     * @param bool   $strict         Whether to do strict comparison.
     */
    public function __construct($field, array $acceptedValues, $strict = true)
    {
        parent::__construct($field);

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
    public function equals(Criteria $other)
    {
        if (1 === count($this->acceptedValues)) {
            // OneOf is logically equivalent to Same if strict and only one value
            if ($this->strict && $other instanceof Same) {
                return $this->field === $other->field
                && reset($this->acceptedValues) === $other->getComparedValue();
            }

            // OneOf is logically equivalent to Equals if not strict and only one value
            if (!$this->strict && $other instanceof Equals) {
                return $this->field === $other->field
                && reset($this->acceptedValues) == $other->getComparedValue();
            }
        }

        return parent::equals($other);
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return in_array($value, $this->acceptedValues, $this->strict);
    }

}
