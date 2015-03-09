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
 * Checks that a field value equals another value.
 *
 * The comparison is done using PHP's "==" equality operator.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Equals extends Atom
{
    /**
     * @var string
     */
    private $comparedValue;

    /**
     * Creates the criterion.
     *
     * @param string $field The field name.
     * @param mixed  $comparedValue The compared value.
     */
    public function __construct($field, $comparedValue)
    {
        parent::__construct($field);

        $this->comparedValue = $comparedValue;
    }

    /**
     * Returns the compared value.
     *
     * @return mixed The compared value.
     */
    public function getComparedValue()
    {
        return $this->comparedValue;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Criteria $other)
    {
        if ($other instanceof OneOf && !$other->isStrict()) {
            return $this->field === $other->field
                && array($this->comparedValue) == $other->getAcceptedValues();
        }

        return parent::equals($other);
    }


    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return $this->comparedValue == $value;
    }
}
