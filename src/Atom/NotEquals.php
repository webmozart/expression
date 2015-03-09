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

/**
 * Checks that a field value does not equal another value.
 *
 * The comparison is done using PHP's "!=" equality operator.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotEquals extends Atom
{
    /**
     * @var mixed
     */
    private $comparedValue;

    /**
     * Creates the criterion.
     *
     * @param string $fieldName     The field name.
     * @param mixed  $comparedValue The compared value.
     */
    public function __construct($fieldName, $comparedValue)
    {
        parent::__construct($fieldName);

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
    protected function matchValue($value)
    {
        return $this->comparedValue != $value;
    }
}
